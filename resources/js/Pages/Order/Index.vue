<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { ref } from "vue";
import ResuableModal from "@/Components/ResuableModal.vue";

const props = defineProps({
  orders: {
    type: Array,
    default: [],
  },
  addresses: {
    type: Array,
    default: [],
  },
});

const selectedAddress = ref({});

const form = useForm({
  addressId: 0,
});

let showModal = ref(false);

const orderForm = useForm({
  id: 0,
  cardholderName: "",
  cardNumber: "",
  expMonth: "",
  expYear: "",
  cvc: "",
});

const payNow = (id) => {
  orderForm.id = id;
  showModal.value = true;
};

const submitPayment = (id) => {
  orderForm.post(route("process.payment", id), {
    onSuccess: () => {},
    preserveState: true,
    preserveScroll: true,
  });
};

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

      <div v-if="orders.length === 0" class="text-center text-gray-500">
        <p>You have no orders yet.</p>
      </div>

      <div v-else>
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
          <button @click="payNow(order.id)" class="btn btn-primary"> Pay Now</button>
        </div>
      </div>

      <ResuableModal
        :classes="['w-[90%] md:w-[80%] lg:w-[60%] h-[80vh]']"
        header="Pay Now"
        :show="showModal"
        @close="showModal = false"
      >
        <template #content>
          <form @submit.prevent="submitPayment" class="p-2">
            <div class="mb-4">
              <label
                for="cardholderName"
                class="block text-sm font-medium text-gray-700"
                >Cardholder's Name</label
              >
              <input
                type="text"
                id="cardholderName"
                v-model="orderForm.cardholderName"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                required
              />
            </div>

            <div class="mb-4">
              <label
                for="cardNumber"
                class="block text-sm font-medium text-gray-700"
                >Card Number</label
              >
              <input
                type="text"
                id="cardNumber"
                v-model="orderForm.cardNumber"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                required
              />
            </div>

            <div class="mb-4">
              <label
                for="expMonth"
                class="block text-sm font-medium text-gray-700"
                >Expiration Month</label
              >
              <input
                type="text"
                id="expMonth"
                v-model="orderForm.expMonth"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                required
              />
            </div>

            <div class="mb-4">
              <label
                for="expYear"
                class="block text-sm font-medium text-gray-700"
                >Expiration Year</label
              >
              <input
                type="text"
                id="expYear"
                v-model="orderForm.expYear"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                required
              />
            </div>

            <div class="mb-4">
              <label for="cvc" class="block text-sm font-medium text-gray-700"
                >CVC</label
              >
              <input
                type="text"
                id="cvc"
                v-model="orderForm.cvc"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                required
              />
            </div>

            <button
              type="submit"
              class="w-full bg-blue-600 text-white py-2 rounded-md shadow-md hover:bg-blue-700 transition duration-150"
              :disabled="orderForm.processing"
            >
              Pay Now
            </button>
          </form>
        </template>
      </ResuableModal>
    </main>
  </AuthLayout>
</template>

<style scoped>
/* Add your custom styles here */
</style>
