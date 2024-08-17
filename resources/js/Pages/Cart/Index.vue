<script setup>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { ref } from "vue";
import { usePage, useForm, Head } from "@inertiajs/vue3";

const { props } = usePage();
const cartItems = ref(props.cartItems);

const removeItem = async (id) => {
  if (confirm("Are you sure you want to remove this item?")) {
    try {
      await axios.post(`/cart/remove/${id}`);
      cartItems.value = cartItems.value.filter((item) => item.id !== id);
    } catch (error) {
      console.error("Error removing item:", error);
    }
  }
};

const updateQuantity = async (id, quantity) => {
  try {
    await axios.post(`/cart/update/${id}`, { quantity });
  } catch (error) {
    console.error("Error updating quantity:", error);
  }
};
</script>

<template>
  <Head title="Cart" />

  <AuthLayout>
    <div class="container mx-auto p-4">
      <h1 class="text-2xl font-bold mb-4">Your Cart</h1>
      <div v-if="cartItems.length === 0" class="text-center">
        <p class="text-lg">Your cart is empty.</p>
      </div>
      <div v-else>
        <div
          v-for="item in cartItems"
          :key="item.id"
          class="bg-white shadow-md rounded-lg p-4 border border-gray-200 mb-4 flex items-center space-x-4"
        >
          <img
            :src="item.product.images[0]?.image || '/images/no_image.jpeg'"
            :alt="item.product.title"
            class="w-24 h-24 object-cover rounded-md border border-gray-300"
          />
          <div class="flex-1">
            <h2 class="text-lg font-semibold">{{ item.product.title }}</h2>
            <p class="text-gray-600">Quantity: {{ item.quantity }}</p>
            <p class="text-gray-600">
              Price: {{ item.product.currency?.symbol + item.product.price }}
            </p>
          </div>
          <div>
            <button
              @click="removeItem(item.id)"
              class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
            >
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
      </div>
    </div>
  </AuthLayout>
</template>
