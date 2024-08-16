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
  name: "",
  active: 0,
});

const massForm = useForm({
  selectedBrands: [],
});

const toggleSelection = (brandId) => {
  const index = massForm.selectedBrands.indexOf(brandId);
  if (index > -1) {
    massForm.selectedBrands.splice(index, 1);
  } else {
    massForm.selectedBrands.push(brandId);
  }
};

const isSelected = (brandId) => massForm.selectedBrands.includes(brandId);

const deleteBrand = (brandId) => {
  if (confirm("Are you sure you want to delete this brand?")) {
    form.delete(route("admin.brands.destroy", brandId), {
      onSuccess: () => {
        massForm.selectedBrands = massForm.selectedBrands.filter(
          (id) => id !== brandId
        );
      },
    });
  }
};

const massDeleteBrands = () => {
  if (confirm("Are you sure you want to delete the selected brands?")) {
    massForm.delete(route("admin.brands.massDestroy"), {
      onSuccess: () => {
        massForm.selectedBrands.value = [];
      },
    });
  }
};

const storeBrand = () => {
  form.post(route("admin.brands.store"), {
    onSuccess: () => {
      showModal.value = false;
      form.reset();
    },
    onError: (errors) => console.error("Form errors:", errors),
  });
};

const updateBrand = () => {
  form.patch(route("admin.brands.update", form.id), {
    onSuccess: () => {
      showModal.value = false;
      form.reset();
    },
    onError: (errors) => console.error("Form errors:", errors),
  });
};

const editBrand = (brandId) => {
  axios
    .get(route("admin.brands.edit", brandId))
    .then((response) => {
      form.id = response.data.id;
      form.name = response.data.name;
      form.active = response.data.active;

      showModal.value = true;
    })
    .catch((error) => console.error(error));
};
</script>

<template>
  <Head :title="'Brands Dashboard'" />

  <AuthLayout>
    <div class="my-2 flex items-center justify-center space-x-2">
      <button class="btn btn-primary" @click="showModal = true">
        Add Brand
      </button>
      <button
        @click="massDeleteBrands"
        class="btn btn-danger"
        :disabled="massForm.selectedBrands.length === 0"
      >
        Delete Selected
      </button>
    </div>

    <!-- Create/Edit Brand Modal -->
    <ResuableModal
      :classes="['w-[90%] md:w-[80%] lg:w-[60%] h-full']"
      :header="form.id ? 'Edit Brand' : 'Create Brand'"
      :show="showModal"
      @close="showModal = false"
    >
      <template #content>
        <form @submit.prevent="storeBrand" class="mx-2">
          <section class="space-y-4">
            <!-- Name -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700"
                >Name</label
              >
              <input
                id="name"
                v-model="form.name"
                type="text"
                placeholder="Enter name"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              />
              <p v-if="form.errors.name" class="text-red-600 text-sm">
                {{ form.errors.name }}
              </p>
            </div>

            <!-- Active -->
            <div class="flex items-center">
              <input
                id="active"
                v-model="form.active"
                :checked="form.active"
                type="checkbox"
                class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <label for="active" class="ml-2 text-sm text-gray-700"
                >Active</label
              >
            </div>

            <div class="flex justify-end">
              <button
                v-if="form.id"
                form="updateBrand"
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                :disabled="form.processing"
              >
                Update Brand
              </button>
              <button
                v-else
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                :disabled="form.processing"
              >
                Create Brand
              </button>
            </div>
          </section>
        </form>
        <form
          @submit.prevent="updateBrand"
          id="updateBrand"
          class="hidden"
        ></form>
      </template>
    </ResuableModal>

    <!-- Brand Table -->
    <div class="flex items-center justify-center min-w-full overflow-x-auto">
      <table class="overflow-auto min-w-full bg-white border border-gray-200 rounded-lg">
        <thead>
          <tr class="bg-gray-100">
            <th class="w-16 p-1 text-left">
              <input
                type="checkbox"
                @click="
                  (e) =>
                    (massForm.selectedBrands = e.target.checked
                      ? props.brands.map((p) => p.id)
                      : [])
                "
                class="form-checkbox"
              />
            </th>
            <th class="p-1 text-left font-semibold">ID</th>
            <th class="p-1 text-left font-semibold">Name</th>
            <th class="p-1 text-left font-semibold">Active</th>
            
            <th class="p-1 text-left font-semibold">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="brand in props.brands"
            :key="brand.id"
            class="border-t hover:bg-gray-50"
          >
            <td class="p-1">
              <input
                type="checkbox"
                :value="brand.id"
                @change="toggleSelection(brand.id)"
                :checked="isSelected(brand.id)"
                class="form-checkbox"
              />
            </td>
            <td class="p-1">{{ brand.id }}</td>
            <td class="p-1">{{ brand.name }}</td>
            <td class="p-1 font-bold text-green-600" v-if="brand.active">
              Yes
            </td>
            <td class="p-1 font-bold text-red-600" v-else>No</td>
            <td class="p-1 flex space-x-2">
              <button @click="editBrand(brand.id)" class="btn btn-success">
                Edit
              </button>
              <button @click="deleteBrand(brand.id)" class="btn btn-danger">
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
