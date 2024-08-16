<script setup>
import { ref } from "vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { Head } from "@inertiajs/vue3";
import axios from "axios";

const props = defineProps({
  products: Array,
});

const selectedProducts = ref([]);

const toggleSelection = (productId) => {
  const index = selectedProducts.value.indexOf(productId);
  if (index > -1) {
    selectedProducts.value.splice(index, 1);
  } else {
    selectedProducts.value.push(productId);
  }
};

const isSelected = (productId) => {
  return selectedProducts.value.includes(productId);
};

const deleteProduct = (productId) => {
  if (confirm("Are you sure you want to delete this product?")) {
    axios.delete(route("products.destroy", productId), {
      preserveScroll: true,
      onSuccess: () => {
        selectedProducts.value = selectedProducts.value.filter(
          (id) => id !== productId
        );
      },
    });
  }
};

const massDeleteProducts = () => {
  if (confirm("Are you sure you want to delete the selected products?")) {
    axios.post(
      route("products.massDestroy"),
      { ids: selectedProducts.value },
      {
        preserveScroll: true,
        onSuccess: () => {
          selectedProducts.value = [];
        },
      }
    );
  }
};

const addProduct = () => {
  axios.get(route("products.create"));
};

const editProduct = (productId) => {
  axios.get(route("products.edit", productId));
};
</script>

<template>
  <Head :title="trans('words.dashboard')" />

  <AuthLayout>
    <div class="mb-4 flex space-x-2">
      <button
        @click="addProduct"
        class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600"
      >
        Add Product
      </button>
      <button
        @click="massDeleteProducts"
        class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600"
        :disabled="selectedProducts.length === 0"
      >
        Delete Selected
      </button>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full bg-white border border-gray-200 rounded-lg">
        <thead>
          <tr class="bg-gray-100">
            <th class="w-16 p-3 text-left">
              <input
                type="checkbox"
                @click="
                  (e) =>
                    (selectedProducts.value = e.target.checked
                      ? products.map((p) => p.id)
                      : [])
                "
                class="form-checkbox"
              />
            </th>
            <th class="p-3 text-left font-semibold">ID</th>
            <th class="p-3 text-left font-semibold">Title</th>
            <th class="p-3 text-left font-semibold">Slug</th>
            <th class="p-3 text-left font-semibold">Quantity</th>
            <th class="p-3 text-left font-semibold">Description</th>
            <th class="p-3 text-left font-semibold">Published</th>
            <th class="p-3 text-left font-semibold">In Stock</th>
            <th class="p-3 text-left font-semibold">Price</th>
            <th class="p-3 text-left font-semibold">Brand</th>
            <th class="p-3 text-left font-semibold">Category</th>
            <th class="p-3 text-left font-semibold">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="product in products"
            :key="product.id"
            class="border-t hover:bg-gray-50"
          >
            <td class="p-3">
              <input
                type="checkbox"
                :value="product.id"
                @change="toggleSelection(product.id)"
                :checked="isSelected(product.id)"
                class="form-checkbox"
              />
            </td>
            <td class="p-3">{{ product.id }}</td>
            <td class="p-3">{{ product.title }}</td>
            <td class="p-3">{{ product.slug }}</td>
            <td class="p-3">{{ product.quantity }}</td>
            <td class="p-3 truncate max-w-xs">{{ product.description }}</td>
            <td class="p-3 font-bold text-green-600" v-if="product.published">
              Yes
            </td>
            <td class="p-3 font-bold text-red-600" v-else>No</td>
            <td class="p-3 font-bold text-green-600" v-if="product.in_stock">
              Yes
            </td>
            <td class="p-3 font-bold text-red-600" v-else>No</td>
            <td class="p-3">{{ product.price }}</td>
            <td class="p-3">{{ product.brand }}</td>
            <td class="p-3">{{ product.category }}</td>
            <td class="p-3 flex space-x-2">
              <button
                @click="editProduct(product.id)"
                class="bg-yellow-500 text-white py-1 px-2 rounded hover:bg-yellow-600"
              >
                Edit
              </button>
              <button
                @click="deleteProduct(product.id)"
                class="bg-red-500 text-white py-1 px-2 rounded hover:bg-red-600"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AuthLayout>
</template>

<style scoped></style>
