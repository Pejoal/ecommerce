<script setup>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head, useForm } from "@inertiajs/vue3";
import axios from "axios";

defineProps({
  mustVerifyEmail: Boolean,
  status: String,
  addresses: {
    type: Array,
    default: [],
  },
});

const form = useForm({
  profile_photo: null,
});

const uploadProfilePhoto = () => {
  form.post(route("profile.photo.update"), {
    preserveScroll: true,
  });
};

const addressForm = useForm({
  id: 0,
  type: "",
  address1: "",
  address2: "",
  city: "",
  state: "",
  zipcode: "",
  country_code: "",
  is_main: false,
});

const addAddress = () => {
  addressForm.post(route("user.address.store"), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      addressForm.reset();
    },
  });
};

const deleteAddress = (id) => {
  addressForm.delete(route("user.address.destroy", id), {
    preserveState: true,
    preserveScroll: true,
  });
};

const editAddress = (id) => {
  axios
    .get(route("user.address.edit", id))
    .then((response) => {
      addressForm.id = response.data.id;
      addressForm.type = response.data.type;
      addressForm.address1 = response.data.address1;
      addressForm.address2 = response.data.address2;
      addressForm.city = response.data.city;
      addressForm.state = response.data.state;
      addressForm.zipcode = response.data.zipcode;
      addressForm.country_code = response.data.country_code;
      addressForm.is_main = response.data.is_main;
    })
    .catch((error) => console.error(error));
};

const massForm = useForm({
  selectedAddresses: [],
});

const isSelected = (id) => massForm.selectedAddresses.includes(id);

const toggleSelection = (id) => {
  const index = massForm.selectedAddresses.indexOf(id);
  if (index > -1) {
    massForm.selectedAddresses.splice(index, 1);
  } else {
    massForm.selectedAddresses.push(id);
  }
};

const massDeleteaddresses = () => {
  if (confirm("Are you sure you want to delete the selected addresses?")) {
    massForm.delete(route("user.address.update.massDestroy"), {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        massForm.selectedAddresses.value = [];
      },
    });
  }
};

const updateAddress = () => {
  addressForm.patch(route("user.address.update", addressForm.id), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      addressForm.reset();
    },
    onError: (errors) => console.error("Form errors:", errors),
  });
};
</script>

<template>
  <Head :title="trans('words.profile')" />

  <AuthLayout>
    <div class="bg-gray-50 pb-2">
      <div class="p-2 sm:p-4 shadow sm:rounded-lg">
        <UpdateProfileInformationForm
          :must-verify-email="mustVerifyEmail"
          :status="status"
          class="max-w-xl"
        />
      </div>

      <div class="p-2 sm:p-4 shadow sm:rounded-lg">
        <UpdatePasswordForm class="max-w-xl" />
      </div>

      <form
        class="p-2 sm:p-4 shadow sm:rounded-lg"
        @submit.prevent="uploadProfilePhoto"
      >
        <section class="flex justify-between flex-col sm:flex-row">
          <div class="my-2">
            <label class="pr-2" for="profile_photo">
              {{ trans("words.profile_photo") }}
            </label>
            <input
              id="profile_photo"
              type="file"
              @input="form.profile_photo = $event.target.files[0]"
            />
          </div>
          <button
            class="btn btn-success"
            type="submit"
            :disabled="form.processing"
          >
            {{ trans("words.upload") }}
          </button>
        </section>
        <p
          v-if="form.errors.profile_photo"
          class="text-sm bg-red-600 rounded-md my-1 px-2 py-1"
        >
          {{ form.errors.profile_photo }}
        </p>
        <progress
          v-if="form.progress"
          :value="form.progress.percentage"
          max="100"
        >
          {{ form.progress.percentage }}%
        </progress>
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.recentlySuccessful" class="text-sm">
            {{ trans("words.uploaded") }}
          </p>
        </Transition>
      </form>
      <!-- Address Management -->
      <div class="p-2 sm:p-4 shadow sm:rounded-lg">
        <h2 class="text-xl font-semibold mb-4">Addresses</h2>

        <!-- Add Address Form -->
        <form @submit.prevent="addAddress" class="pb-4 border-b border-black">
          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <label for="type">Address Type</label>
              <input
                v-model="addressForm.type"
                type="text"
                id="type"
                class="w-full border border-gray-300 rounded-md p-2"
                required
              />
              <Transition
                enter-from-class="opacity-0"
                leave-to-class="opacity-0"
                class="transition ease-in-out"
              >
                <p v-if="addressForm.errors.type" class="text-sm text-red-600">
                  {{ addressForm.errors.type }}
                </p>
              </Transition>
            </div>

            <div>
              <label for="address1">Address 1</label>
              <input
                v-model="addressForm.address1"
                type="text"
                id="address1"
                class="w-full border border-gray-300 rounded-md p-2"
                required
              />
              <Transition
                enter-from-class="opacity-0"
                leave-to-class="opacity-0"
                class="transition ease-in-out"
              >
                <p
                  v-if="addressForm.errors.address1"
                  class="text-sm text-red-600"
                >
                  {{ addressForm.errors.address1 }}
                </p>
              </Transition>
            </div>

            <div>
              <label for="address2">Address 2</label>
              <input
                v-model="addressForm.address2"
                type="text"
                id="address2"
                class="w-full border border-gray-300 rounded-md p-2"
              />
              <Transition
                enter-from-class="opacity-0"
                leave-to-class="opacity-0"
                class="transition ease-in-out"
              >
                <p
                  v-if="addressForm.errors.address2"
                  class="text-sm text-red-600"
                >
                  {{ addressForm.errors.address2 }}
                </p>
              </Transition>
            </div>

            <div>
              <label for="city">City</label>
              <input
                v-model="addressForm.city"
                type="text"
                id="city"
                class="w-full border border-gray-300 rounded-md p-2"
                required
              />
              <Transition
                enter-from-class="opacity-0"
                leave-to-class="opacity-0"
                class="transition ease-in-out"
              >
                <p v-if="addressForm.errors.city" class="text-sm text-red-600">
                  {{ addressForm.errors.city }}
                </p>
              </Transition>
            </div>

            <div>
              <label for="state">State</label>
              <input
                v-model="addressForm.state"
                type="text"
                id="state"
                class="w-full border border-gray-300 rounded-md p-2"
              />
              <Transition
                enter-from-class="opacity-0"
                leave-to-class="opacity-0"
                class="transition ease-in-out"
              >
                <p v-if="addressForm.errors.state" class="text-sm text-red-600">
                  {{ addressForm.errors.state }}
                </p>
              </Transition>
            </div>

            <div>
              <label for="zipcode">Zipcode</label>
              <input
                v-model="addressForm.zipcode"
                type="text"
                id="zipcode"
                class="w-full border border-gray-300 rounded-md p-2"
                required
              />
              <Transition
                enter-from-class="opacity-0"
                leave-to-class="opacity-0"
                class="transition ease-in-out"
              >
                <p
                  v-if="addressForm.errors.zipcode"
                  class="text-sm text-red-600"
                >
                  {{ addressForm.errors.zipcode }}
                </p>
              </Transition>
            </div>

            <div>
              <label for="country_code">Country Code</label>
              <input
                v-model="addressForm.country_code"
                type="text"
                id="country_code"
                class="w-full border border-gray-300 rounded-md p-2"
                required
              />

              <Transition
                enter-from-class="opacity-0"
                leave-to-class="opacity-0"
                class="transition ease-in-out"
              >
                <p
                  v-if="addressForm.errors.country_code"
                  class="text-sm text-red-600"
                >
                  {{ addressForm.errors.country_code }}
                </p>
              </Transition>
            </div>
          </div>

          <section>
            <div class="flex items-center space-x-2 p-2">
              <input
                id="is_main"
                type="checkbox"
                v-model="addressForm.is_main"
                :checked="addressForm.is_main"
                class="h-5 w-5 text-blue-600 transition duration-150 ease-in-out rounded-full"
              />
              <label for="is_main" class="text-gray-700"> Main Address </label>
            </div>
            <Transition
              enter-from-class="opacity-0"
              leave-to-class="opacity-0"
              class="transition ease-in-out"
            >
              <p v-if="addressForm.errors.is_main" class="text-sm text-red-600">
                {{ addressForm.errors.is_main }}
              </p>
            </Transition>
          </section>

          <button
            v-if="!addressForm.id"
            type="submit"
            class="btn btn-primary mt-4"
            :disabled="addressForm.processing"
          >
            Add Address
          </button>

          <button
            v-else
            type="submit"
            class="btn btn-success mt-4"
            form="updateAddress"
            :disabled="addressForm.processing"
          >
            Update Address
          </button>
        </form>
        <form
          @submit.prevent="updateAddress"
          id="updateAddress"
          class="hidden"
        ></form>

        <!-- Display Addresses -->
        <div v-if="addresses.length === 0" class="text-center">
          <p class="text-lg">No addresses found.</p>
        </div>
        <div v-else class="overflow-x-auto w-full">
          <table class="w-full bg-white border border-gray-200 rounded-lg">
            <thead>
              <tr class="bg-gray-100">
                <th class="w-16 p-1 text-left">
                  <input
                    type="checkbox"
                    @click="
                      (e) =>
                        (massForm.selectedAddresses = e.target.checked
                          ? addresses.map((p) => p.id)
                          : [])
                    "
                    class="form-checkbox"
                  />
                </th>
                <th class="p-1 text-left font-semibold">ID</th>
                <th class="p-1 text-left font-semibold">Type</th>
                <th class="p-1 text-left font-semibold">Address 1</th>
                <th class="p-1 text-left font-semibold">Address 2</th>
                <th class="p-1 text-left font-semibold">City</th>
                <th class="p-1 text-left font-semibold">State</th>
                <th class="p-1 text-left font-semibold">Zip Code</th>
                <th class="p-1 text-left font-semibold">Is Main</th>
                <th class="p-1 text-left font-semibold">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="address in addresses"
                :key="address.id"
                class="border-t hover:bg-gray-50"
              >
                <td class="p-1">
                  <input
                    type="checkbox"
                    :value="address.id"
                    @change="toggleSelection(address.id)"
                    :checked="isSelected(address.id)"
                    class="form-checkbox"
                  />
                </td>
                <td class="p-1">{{ address.id }}</td>
                <td class="p-1">{{ address.type }}</td>
                <td class="p-1">{{ address.address1 }}</td>
                <td class="p-1">{{ address.address2 }}</td>
                <td class="p-1">{{ address.city }}</td>
                <td class="p-1">{{ address.state }}</td>
                <td class="p-1">{{ address.zipcode }}</td>
                <td v-if="address.is_main" class="p-1 text-green-500 font-bold">
                  Yes
                </td>
                <td v-else class="p-1 text-red-500 font-bold">No</td>
                <td class="p-1 flex space-x-2">
                  <button
                    @click="editAddress(address.id)"
                    class="btn btn-success"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteAddress(address.id)"
                    class="btn btn-danger"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="p-2 flex items-center gap-2">
            <button
              @click="massDeleteaddresses"
              class="btn btn-danger"
              :disabled="massForm.selectedAddresses?.length === 0"
            >
              Delete Selected
            </button>
          </div>
        </div>
      </div>

      <div class="p-2 sm:p-4 shadow sm:rounded-lg">
        <DeleteUserForm class="max-w-xl" />
      </div>
    </div>
  </AuthLayout>
</template>
