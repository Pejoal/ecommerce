<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { Head } from "@inertiajs/vue3";
import ResuableModal from "@/Components/ResuableModal.vue";
import axios from "axios";

const props = defineProps({
  brands: {
    type: Array,
    default: [],
  },
});

let showModal = ref(false);

const form = useForm({
  id: null,
  brand_id: "",
  category_id: "",
});

const massForm = useForm({
  selectedProducts: [],
});

const toggleSelection = (productId) => {
  const index = massForm.selectedProducts.indexOf(productId);
  if (index > -1) {
    massForm.selectedProducts.splice(index, 1);
  } else {
    massForm.selectedProducts.push(productId);
  }
};

const isSelected = (productId) => massForm.selectedProducts.includes(productId);

const deleteProduct = (productId) => {
  if (confirm("Are you sure you want to delete this product?")) {
    form.delete(route("admin.products.destroy", productId), {
      onSuccess: () => {
        massForm.selectedProducts = massForm.selectedProducts.filter(
          (id) => id !== productId
        );
      },
    });
  }
};

const massDeleteProducts = () => {
  if (confirm("Are you sure you want to delete the selected products?")) {
    massForm.delete(route("admin.products.massDestroy"), {
      onSuccess: () => {
        massForm.selectedProducts.value = [];
      },
    });
  }
};

const storeProduct = () => {
  form.post(route("admin.products.store"), {
    onSuccess: () => {
      showModal.value = false;
      form.reset();
    },
    onError: (errors) => console.error("Form errors:", errors),
  });
};

const updateProduct = () => {
  form.patch(route("admin.products.update", form.id), {
    onSuccess: () => {
      showModal.value = false;
      form.reset();
    },
    onError: (errors) => console.error("Form errors:", errors),
  });
};

const editProduct = (productId) => {
  axios
    .get(route("admin.products.edit", productId))
    .then((response) => {
      form.id = response.data.id;
      form.brand_id = response.data.brand_id;
      form.category_id = response.data.category_id;

      showModal.value = true;
    })
    .catch((error) => console.error(error));
};
</script>

<template>
  <Head :title="'Products Dashboard'" />

  <AuthLayout>
    <div class="my-2 flex space-x-2">
      <button class="btn btn-primary" @click="showModal = true">
        Add Product
      </button>
      <button
        @click="massDeleteProducts"
        class="btn btn-danger"
        :disabled="massForm.selectedProducts.length === 0"
      >
        Delete Selected
      </button>
    </div>

    <!-- Create/Edit Product Modal -->
    <ResuableModal
      :classes="['w-[90%] md:w-[80%] lg:w-[60%] h-full']"
      :header="form.id ? 'Edit Product' : 'Create Product'"
      :show="showModal"
      @close="showModal = false"
    >
      <template #content>
        <form @submit.prevent="storeProduct" class="mx-2">
          <section class="space-y-4">
            <!-- Title -->
            <div>
              <label for="title" class="block text-sm font-medium text-gray-700"
                >Title</label
              >
              <input
                id="title"
                v-model="form.title"
                type="text"
                placeholder="Enter title"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              />
              <p v-if="form.errors.title" class="text-red-600 text-sm">
                {{ form.errors.title }}
              </p>
            </div>

            <!-- Quantity -->
            <div>
              <label
                for="quantity"
                class="block text-sm font-medium text-gray-700"
                >Quantity</label
              >
              <input
                id="quantity"
                v-model="form.quantity"
                type="number"
                placeholder="Enter quantity"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              />
              <p v-if="form.errors.quantity" class="text-red-600 text-sm">
                {{ form.errors.quantity }}
              </p>
            </div>

            <!-- Published -->
            <div class="flex items-center">
              <input
                id="published"
                v-model="form.published"
                :checked="form.published"
                type="checkbox"
                class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <label for="published" class="ml-2 text-sm text-gray-700"
                >Published</label
              >
            </div>

            <!-- In Stock -->
            <div class="flex items-center">
              <input
                id="in_stock"
                v-model="form.in_stock"
                :checked="form.in_stock"
                type="checkbox"
                class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <label for="in_stock" class="ml-2 text-sm text-gray-700"
                >In Stock</label
              >
            </div>

            <div class="flex justify-end">
              <button
                v-if="form.id"
                form="updateProduct"
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                :disabled="form.processing"
              >
                Update Product
              </button>
              <button
                v-else
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                :disabled="form.processing"
              >
                Create Product
              </button>
            </div>
          </section>
        </form>
        <form
          @submit.prevent="updateProduct"
          id="updateProduct"
          class="hidden"
        ></form>
      </template>
    </ResuableModal>

    <!-- Product Table -->
    <div class="min-w-full overflow-x-auto">
      <table class="overflow-auto bg-white border border-gray-200 rounded-lg">
        <thead>
          <tr class="bg-gray-100">
            <th class="w-16 p-1 text-left">
              <input
                type="checkbox"
                @click="
                  (e) =>
                    (massForm.selectedProducts = e.target.checked
                      ? props.products.map((p) => p.id)
                      : [])
                "
                class="form-checkbox"
              />
            </th>
            <th class="p-1 text-left font-semibold">ID</th>
            <th class="p-1 text-left font-semibold">Published</th>
            <th class="p-1 text-left font-semibold">Brand</th>
            <th class="p-1 text-left font-semibold">Category</th>
            <th class="p-1 text-left font-semibold">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="product in props.products"
            :key="product.id"
            class="border-t hover:bg-gray-50"
          >
            <td class="p-1">
              <input
                type="checkbox"
                :value="product.id"
                @change="toggleSelection(product.id)"
                :checked="isSelected(product.id)"
                class="form-checkbox"
              />
            </td>
            <td class="p-1">{{ product.id }}</td>
            <td class="p-1">{{ product.title }}</td>

            <td class="p-1">{{ product.brand }}</td>
            <td class="p-1">{{ product.category }}</td>
            <td class="p-1 flex space-x-2">
              <button @click="editProduct(product.id)" class="btn btn-success">
                Edit
              </button>
              <button @click="deleteProduct(product.id)" class="btn btn-danger">
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
