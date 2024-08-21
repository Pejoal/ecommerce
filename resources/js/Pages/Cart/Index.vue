<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";

const props = defineProps({
  cartItems: {
    type: Object,
    default: {},
  },
  savedForLaterItems: {
    type: Object,
    default: {},
  },
  cartItemsTotalPrice: {
    type: Number,
    default: 0,
  },
  savedForLaterItemsTotalPrice: {
    type: Number,
    default: 0,
  },
});

const form = useForm({ quantity: 0 });

const removeItem = (id) => {
  if (confirm("Are you sure you want to remove this item?")) {
    form.delete(route("cart.destroy", id), {
      preserveState: true,
      preserveScroll: true,
    });
  }
};

const updateQuantity = (id, quantity) => {
  form.quantity = quantity;
  form.patch(route("cart.update", id), {
    preserveState: true,
    preserveScroll: true,
  });
};

const moveToCart = (id) => {
  form.post(route("cart.moveToCart", id), {
    preserveState: true,
    preserveScroll: true,
  });
};

const saveForLater = (id) => {
  form.post(route("cart.saveForLater", id), {
    preserveState: true,
    preserveScroll: true,
  });
};

const orderNow = () => {
  form.post(route("order.store"), {
    onSuccess: (response) => {},
    onError: (response) => {},
  });
};
</script>

<template>
  <Head title="Cart" />

  <AuthLayout>
    <main class="p-1">
      <h1 class="text-2xl font-bold pb-2">Your Cart</h1>
      <section v-if="cartItems.length === 0" class="text-center">
        <p class="text-lg">Your cart is empty.</p>
      </section>
      <section v-else>
        <div
          v-for="item in cartItems"
          :key="item.id"
          class="shadow-md rounded-lg p-1 border border-gray-200 mb-4 flex items-center space-x-2"
        >
          <img
            :src="item.product.images[0]?.image || '/images/no_image.jpeg'"
            :alt="item.product.title"
            class="w-20 h-20 object-cover rounded-md border border-gray-300"
          />
          <div class="flex-1">
            <h2 class="text-lg font-semibold">{{ item.product.title }}</h2>
            <p class="text-gray-600">Quantity: {{ item.quantity }}</p>
            <p class="text-gray-600">
              Price: {{ item.product.currency?.symbol + item.product.price }}
            </p>
          </div>

          <div>
            <button @click="saveForLater(item.id)" class="btn btn-primary">
              Save for later
            </button>
          </div>

          <div>
            <button @click="removeItem(item.id)" class="btn btn-danger">
              Remove
            </button>
          </div>
          <div>
            <input
              v-model.number="item.quantity"
              @change="updateQuantity(item.id, item.quantity)"
              type="number"
              min="1"
              class="w-16 px-2 py-1 border border-gray-300 rounded"
            />
          </div>
        </div>

        <section class="flex items-center justify-center text-lg mb-2 gap-2">
          <p>
            Total Price:&nbsp;
            <strong> ${{ cartItemsTotalPrice.toFixed(2) }} </strong>
          </p>

          <form @submit.prevent="orderNow">
            <button
              type="submit"
              class="btn btn-success"
              :disabled="form.processing"
            >
              Order Now
            </button>
          </form>

          <!-- Error Message -->
          <Transition
            enter-from-class="opacity-0"
            leave-to-class="opacity-0"
            class="transition ease-in-out"
          >
            <p v-if="$page.props.error" class="text-red-600 text-sm">
              {{ $page.props.error }}
            </p>
          </Transition>
        </section>
      </section>

      <!-- Saved for Later Items Section -->
      <h1 class="text-2xl font-bold py-2 border-t border-black">
        Saved for Later
      </h1>
      <section v-if="savedForLaterItems.length === 0" class="text-center">
        <p class="text-lg">No items saved for later.</p>
      </section>
      <section v-else>
        <div
          v-for="item in savedForLaterItems"
          :key="item.id"
          class="bg-white shadow-md rounded-lg p-1 border border-gray-200 mb-4 flex items-center space-x-2"
        >
          <img
            :src="item.product.images[0]?.image || '/images/no_image.jpeg'"
            :alt="item.product.title"
            class="w-20 h-20 object-cover rounded-md border border-gray-300"
          />
          <div class="flex-1">
            <h2 class="text-lg font-semibold">{{ item.product.title }}</h2>
            <p class="text-gray-600">
              Price: {{ item.product.currency?.symbol + item.product.price }}
            </p>
          </div>
          <div>
            <button @click="moveToCart(item.id)" class="btn btn-primary">
              Move to Cart
            </button>
          </div>
        </div>

        <h3 class="flex items-center justify-center text-lg mb-2">
          Total Price:&nbsp;
          <strong> ${{ savedForLaterItemsTotalPrice.toFixed(2) }} </strong>
        </h3>
      </section>
    </main>
  </AuthLayout>
</template>
