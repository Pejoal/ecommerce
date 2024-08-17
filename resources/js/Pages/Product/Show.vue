<script setup>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
  product: {
    type: Object,
    default: {},
  },
});

const form = useForm({
  product_id: props.product.id,
  quantity: 1,
});

const addToCart = () => {
  form.post(route("cart.add"), {
    preserveState: true,
    preserveScroll: true,
  });
};

const saveForLater = () => {
  // Implement the logic to save the product for later
};

const buyNow = () => {
  // Implement the logic to proceed with buying the product immediately
};
</script>

<template>
  <Head :title="props.product.title" />

  <AuthLayout>
    <div class="flex flex-col lg:flex-row shadow-md rounded-lg p-2">
      <!-- Product Image Section -->
      <div class="lg:w-1/2">
        <img
          :src="props.product.images[0]?.image || '/images/no_image.jpeg'"
          alt="Product Image"
          class="w-full h-auto object-cover rounded-md"
        />
        
        <div class="overflow-auto flex gap-2 mt-4">
          <img
            v-for="(image, index) in props.product.images?.slice(1)"
            :key="index"
            :src="image?.image"
            alt="Product Thumbnail"
            class="w-40 h-40 object-cover rounded-md border border-gray-300 cursor-pointer hover:opacity-75"
          />
        </div>
      </div>

      <!-- Product Details Section -->
      <div class="lg:w-1/2 lg:pl-8 mt-6 lg:mt-0">
        <h1 class="text-3xl font-semibold mb-2">
          {{ props.product.title }}
        </h1>
        <p class="text-gray-600 mb-4">{{ props.product.description }}</p>

        <div class="flex items-center mb-4">
          <span class="text-2xl font-bold text-green-600">{{
            props.product.currency.symbol + props.product.price
          }}</span>
          <span
            v-if="props.product.in_stock"
            class="ml-4 text-sm text-green-600 font-semibold"
            >In Stock</span
          >
          <span v-else class="ml-4 text-sm text-red-600 font-semibold"
            >Out of Stock</span
          >
        </div>

        <!-- Quantity Selector -->
        <div class="mb-4">
          <label for="quantity" class="block text-sm font-medium text-gray-700"
            >Quantity</label
          >
          <select
            id="quantity"
            v-model="form.quantity"
            class="mt-1 block w-20 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
          >
            <option v-for="n in props.product.quantity" :key="n" :value="n">
              {{ n }}
            </option>
          </select>

          <p v-if="form.errors.quantity" class="text-red-600 text-sm">
            {{ form.errors.quantity }}
          </p>
        </div>

        <!-- Add to Cart & Other Buttons -->
        <div class="flex flex-col gap-4 mt-6">
          <button
            @click="addToCart"
            class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            :disabled="!props.product.in_stock"
          >
            Add to Cart
          </button>
          <button
            @click="saveForLater"
            class="w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
          >
            Save for Later
          </button>
          <button
            @click="buyNow"
            class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
            :disabled="!props.product.in_stock"
          >
            Buy Now
          </button>
        </div>

        <!-- Product Meta Info -->
        <div class="mt-6">
          <p>
            <span class="font-medium text-gray-800">Brand:</span>
            {{ props.product.brand.name }}
          </p>
          <p>
            <span class="font-medium text-gray-800">Category:</span>
            {{ props.product.category.name }}
          </p>
          <p v-if="props.product.premium_delivery">
            <span class="font-medium text-gray-800">Premium Delivery:</span>
            Available
          </p>
          <p v-else>
            <span class="font-medium text-gray-800">Premium Delivery:</span>
            Not Available
          </p>
        </div>
      </div>
    </div>
  </AuthLayout>
</template>
