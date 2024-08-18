<script setup>
import { Head } from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";

const props = defineProps({
  orders: {
    type: Array,
    default: [],
  },
});
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

          <h3 class="text-md font-semibold mb-2">Items:</h3>
          <ul>
            <li
              v-for="item in order.items"
              :key="item.id"
              class="mb-2 flex justify-between border-b border-gray-300 pb-2"
            >
              <span>{{ item.product.title }} (x{{ item.quantity }})</span>
              <span
                >${{ (item.product.price * item.quantity).toFixed(2) }}</span
              >
            </li>
          </ul>
        </div>
      </div>
    </main>
  </AuthLayout>
</template>

<style scoped>
/* Add your custom styles here */
</style>
