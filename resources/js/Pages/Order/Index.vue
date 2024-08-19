<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { ref, onMounted } from "vue";
import { loadStripe } from "@stripe/stripe-js";
import ResuableModal from "@/Components/ResuableModal.vue";
import { Transition } from "vue";

const props = defineProps({
  orders: {
    type: Array,
    default: [],
  },
  addresses: {
    type: Array,
    default: [],
  },
  clientSecret: {
    type: String,
    default: "",
  },
  success: {
    type: String,
    default: "",
  },
  error: {
    type: String,
    default: "",
  },
});

const stripePromise = loadStripe(import.meta.env.VITE_STRIPE_KEY);
const cardElement = ref(null);
let card;

const orderForm = useForm({
  id: 0,
  cardholderName: "",
  cardNumber: "",
  expMonth: "",
  expYear: "",
  cvc: "",
  payment_method: "",
});

const showModal = ref(false);
const payError = ref("");

onMounted(async () => {
  const stripe = await stripePromise;
  const elements = stripe.elements();

  card = elements.create("card");
  card.mount(cardElement.value);
});

const payNow = (id) => {
  orderForm.id = id;
  showModal.value = true;
};

const handlePayment = async (id) => {
  const stripe = await stripePromise;

  const { error, paymentMethod } = await stripe.createPaymentMethod({
    type: "card",
    card: card,
    billing_details: {
      name: orderForm.cardholderName,
    },
  });

  if (error) {
    payError.value = error;
    return;
  }

  orderForm.payment_method = paymentMethod.id;
  orderForm.post(route("process.payment"), {
    onSuccess: () => {
      // Handle successful payment here
      if (props.clientSecret) {
        stripe.confirmCardPayment(props.clientSecret);
      }
    },
    onError: () => {
      // Handle errors
    },
  });
};

const selectedAddress = ref({});

const form = useForm({
  addressId: 0,
});

const saveAddress = (orderId, addressId) => {
  // Here you would make an API request to save the selected address for the order
  form.addressId = addressId;
  form.patch(route("order.address.update", orderId), {
    onSuccess: () => {
      productSuccess.value[id] = true;
    },
    preserveState: true,
    preserveScroll: true,
  });
};
</script>

<template>
  <Head title="Your Orders" />

  <AuthLayout>
    <main class="p-4 bg-gray-100 min-h-screen">
      <h1 class="text-2xl font-bold">Your Orders</h1>

      <section v-if="orders.length === 0" class="text-center text-gray-500">
        <p>You have no orders yet.</p>
      </section>

      <section v-else>
        <div
          v-for="order in orders"
          :key="order.id"
          class="bg-white shadow-md rounded-lg mb-4 p-4 border border-gray-200"
        >
          <h2 class="text-lg font-semibold mb-2">Order #{{ order.id }}</h2>
          <p class="text-gray-600 mb-2">
            Total Price: ${{ order.total_price.toFixed(2) }}
          </p>

          <template v-if="order.address">
            <p class="text-gray-600 mb-2">Address:</p>
            <address class="mb-4">
              <p>{{ order.address.address1 }}</p>
              <p v-if="order.address.address2">{{ order.address.address2 }}</p>
              <p>
                {{ order.address.city }}, {{ order.address.state }}
                {{ order.address.zipcode }}
              </p>
              <p>{{ order.address.country_code }}</p>
            </address>
          </template>

          <template v-else>
            <p class="text-red-500 mb-2">No address provided for this order.</p>
            <select
              v-model="selectedAddress[order.id]"
              class="border border-gray-300 rounded-md p-2 mb-4"
            >
              <option disabled value="">Select an Address</option>
              <option
                v-for="address in addresses"
                :key="address.id"
                :value="address.id"
              >
                {{ address.address1 }}, {{ address.city }},
                {{ address.zipcode }}
              </option>
            </select>
            <button
              @click="saveAddress(order.id, selectedAddress[order.id])"
              class="btn btn-primary"
              :disabled="!selectedAddress[order.id]"
            >
              Save Address
            </button>
          </template>

          <h3 class="text-md font-semibold mb-2">Items:</h3>
          <ul>
            <li
              v-for="item in order.items"
              :key="item.id"
              class="mb-2 flex justify-between border-b border-gray-300 pb-2"
            >
              <Link
                :href="route('product.show', item.product.slug)"
                class="hover:font-bold"
              >
                <span>{{ item.product.title }} (x{{ item.quantity }})</span>
                <span
                  >${{ (item.product.price * item.quantity).toFixed(2) }}</span
                >
              </Link>
            </li>
          </ul>
          <button @click="payNow(order.id)" class="btn btn-primary">
            Pay Now
          </button>
        </div>
      </section>

      <ResuableModal
        :classes="['w-[90%] md:w-[80%] lg:w-[60%] h-3/4']"
        header="Pay Now"
        :show="showModal"
        @close="showModal = false"
      >
        <template #content>
          <form @submit.prevent="handlePayment" class="p-2">
            <label for="cardholderName">Cardholder's Name</label>
            <input
              type="text"
              v-model="orderForm.cardholderName"
              required
            /><br />

            <div id="card-element" ref="cardElement"></div>
            <br />

            <button
              type="submit"
              class="btn btn-primary"
              :disabled="orderForm.processing"
            >
              Submit Payment
            </button>

            <Transition
              enter-from-class="opacity-0"
              leave-to-class="opacity-0"
              class="transition ease-in-out"
            >
              <section>
                <p class="font-semibold text-green-600" v-if="props.success">
                  {{ props.success }}
                </p>
                <p class="font-semibold text-red-600" v-if="props.error">
                  {{ props.error }}
                </p>
                <p class="font-semibold text-red-600" v-if="payError">
                  {{ payError?.message }}
                </p>
              </section>
            </Transition>
          </form>
        </template>
      </ResuableModal>
    </main>
  </AuthLayout>
</template>

<style scoped>
/* Add your custom styles here */
</style>
