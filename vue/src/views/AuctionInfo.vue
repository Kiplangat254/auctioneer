<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ model.id ? model.title : "Auctioneer Information" }}
                </h1>
            </div>
        </template>
        <form @submit.prevent="saveInformation">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" v-model="model.name" autocomplete="auctioneer_name"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-100 rounded-md"
                                placeholder="Name of Auctioneer" />
                        </div>
                    </div>

                    <!--/ Name -->
                    <!-- Telephone -->
                    <div>
                        <label for="telephone" class="block text-sm font-medium text-gray-700">Telephone</label>
                        <div class="mt-1">
                            <input type="text" name="telephone" id="telephone" v-model="model.title"
                                autocomplete="survey_telephone"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-100 rounded-md"
                                placeholder="Auctioneer Telephone Number" />
                        </div>
                    </div>
                    <!--/ Telephone -->
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1">
                            <input type="text" name="email" id="email" v-model="model.email"
                                autocomplete="auctioneer_email"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-100 rounded-md"
                                placeholder="Auctioneer email" />
                        </div>
                    </div>
                    <!--/ Email -->
                    <!-- Website -->
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                        <div class="mt-1">
                            <input type="text" name="website" id="website" v-model="model.website"
                                autocomplete="auctioneer_website"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-100 rounded-md"
                                placeholder="Auctioneer Website (if any)" />
                        </div>
                    </div>
                    <!--/ Website -->
                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <div class="mt-1">
                            <input type="text" name="location" id="location" v-model="model.location"
                                autocomplete="auctioneer_location"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-100 rounded-md"
                                placeholder="Location of auctioneer offices" />
                        </div>
                    </div>
                    <!--/ Location -->
                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <div class="mt-1">
                            <input type="text" name="address" id="address" v-model="model.address"
                                autocomplete="survey_address"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-100 rounded-md"
                                placeholder="Current Auctioneer Address" />
                        </div>
                    </div>
                    <!--/ Address -->

                    <div class="px-4 py-1 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="
          inline-flex
          justify-center
          py-1
          px-4
          border border-transparent
          shadow-sm
          text-sm
          font-medium
          rounded-md
          text-white
          bg-indigo-600
          hover:bg-indigo-700
          focus:outline-none
          focus:ring-1
          focus:ring-offset-1
          focus:ring-indigo-500
          ">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </PageComponent>
</template>

<script setup>
import { ref } from "vue";
import { useRoute } from "vue-router";

import PageComponent from "../components/PageComponent.vue";

const route = useRoute();

let model = ref({
    name: "",
    telephone: "",
    cell: "",
    email: "",
    website: "",
    location: "",
    address: "",
});




function saveInformation() {
    let action = "created";
    if (model.value.id) {
        action = "updated";
    }
    store.dispatch("saveInformation", { ...model.value }).then(({ data }) => {
        store.commit("notify", {
            type: "success",
            message: "Information was successfully " + action,
        });
        router.push({
            name: "AuctionView",
            params: { id: data.data.id },
        });
    });
}

</script>

