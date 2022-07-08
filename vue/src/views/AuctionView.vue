<template>
  <PageComponent>
    <template v-slot:header>
      <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ model.id ? model.title : "Create Auction" }}
        </h1>
        <router-link :to="{ name: 'AuctionInfo' }" class="
          py-2
          px-3
          text-white
          bg-emerald-500
          rounded-md
          hover:bg-emerald-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 -mt-1 inline-block" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
          Auctioneer Information
        </router-link>
      </div>
    </template>
    <form @submit.prevent="saveAuction">
      <div class="shadow sm:rounded-md sm:overflow-hidden">
        <!-- Survey Fields -->
        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
          <!-- Image -->
          <div>
            <label class="block text-sm font-medium text-gray-700">
              Image
            </label>
            <div class="mt-1 flex items-center">
              <img v-if="model.image" :src="model.image" :alt="model.title" class="w-64 h-48 object-cover" />
              <span v-else class="flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-[80%] w-[80%] text-gray-300" viewBox="0 0 20 20"
                  fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                    clip-rule="evenodd" />
                </svg>
              </span>
              <button type="button"
                class="relative overflow-hidden ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <input type="file" @change="onImageChoose"
                  class="absolute left-0 top-0 right-0 bottom-0 opacity-0 cursor-pointer" />
                Change
              </button>
            </div>
          </div>
          <!--/ Image -->

          <!-- Title -->
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700">
              Title
            </label>
            <div class="mt-1">
              <textarea id="title" name="title" rows="3" v-model="model.title" autocomplete="auction_title"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                placeholder="SALE ON TUESDAY 11TH MAY,2022 AS FROM 11:00 AM AT XXX BUILDING, 2ND FLOOR,XXX ROAD,MOMBASA" />
            </div>
          </div>
          <!--/ Title -->

          <!-- Description -->
          <div>
            <label for="about" class="block text-sm font-medium text-gray-700">
              Description
            </label>
            <div class="mt-1">
              <textarea id="description" name="description" rows="4" v-model="model.description"
                autocomplete="auction_description"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                placeholder="All that property known as xxx registered in the name of xxx " />
            </div>
          </div>
          <!-- Description -->

          <!-- Expire Date -->
          <div>
            <label for="expire_date" class="block text-sm font-medium text-gray-700">Expire Date</label>
            <input type="date" name="expire_date" id="expire_date" v-model="model.expire_date"
              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
          </div>
          <!--/ Expire Date -->
          <!-- Status -->
          <div class="flex items-start">
            <div class="flex items-center h-5">
              <input id="status" name="status" type="checkbox" v-model="model.status"
                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
            </div>
            <div class="ml-3 text-sm">
              <label for="status" class="font-medium text-gray-700">Active</label>
            </div>
          </div>
          <!--/ Status -->
        </div>
        <!--/ Survey Fields -->

        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
          <h3 class="text-2xl font-semibold flex items-center justify-between">
            Conditions Of Sale

            <!-- Add new question -->
            <button type="button" @click="addCondition()"
              class="flex items-center text-sm py-1 px-4 rounded-sm text-white bg-gray-600 hover:bg-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                  d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                  clip-rule="evenodd" />
              </svg>
              Conditions of Sale
            </button>
            <!--/ Add new question -->
          </h3>
          <div v-if="!model.conditions.length" class="text-center text-gray-600">
            You don't have Conditions Of Sale
          </div>
          <div v-for="(condition, index) in model.conditions" :key="condition.id">
            <ConditionEditor :condition="condition" :index="index" @change="conditionChange"
              @addCondition="addCondition" @deleteCondition="deleteCondition" />
          </div>
        </div>

        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
          <button type="submit" class="
          inline-flex
          justify-center
          py-2
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
          focus:ring-2
          focus:ring-offset-2
          focus:ring-indigo-500
          ">
            Save
          </button>
        </div>
      </div>
    </form>

  </PageComponent>
</template>

<script setup>
import { v4 as uuidv4 } from "uuid";
import { ref } from "vue";
import { useRoute } from "vue-router";

import PageComponent from "../components/PageComponent.vue";
import ConditionEditor from "../components/editor/ConditionEditor.vue";

const route = useRoute();

let model = ref({
  title: "",
  slug: "",
  status: false,
  description: null,
  image: null,
  image_url: null,
  expire_date: null,
  conditions: [],
});

if (route.params.id) {
  model.value = store.state.auctions.find(
    (a) => a.id === parseInt(route.params.id)
  );
}

// If the current component is rendered on survey update route we make a request to fetch survey
if (route.params.id) {
  store.dispatch("getAuction", route.params.id);
}

function onImageChoose(ev) {
  const file = ev.target.files[0];

  const reader = new FileReader();
  reader.onload = () => {
    // The field to send on backend and apply validations
    model.value.image = reader.result;

    // The field to display here
    model.value.image_url = reader.result;
    ev.target.value = "";
  };
  reader.readAsDataURL(file);
}

function addCondition(index) {
  const newCondition = {
    id: uuidv4(),
    type: "text",
    condition: "",
  };


  model.value.conditions.splice(index, 500, newCondition);

}

function deleteCondition(condition) {
  model.value.conditions = model.value.conditions.filter((c) => c !== condition);
}

function conditionChange(condition) {
  // Important to explicitelly assign condition.data.options, because otherwise it is a Proxy object
  // and it is lost in JSON.stringify()
  if (condition.data) {
    condition.data = [...condition.data];
  }
  model.value.conditions = model.value.conditions.map((c) => {
    if (c.id === condition.id) {
      return JSON.parse(JSON.stringify(condition));
    }
    return c;
  });
}

/**
 * Create or update auction
 */
function saveAuction() {
  let action = "created";
  if (model.value.id) {
    action = "updated";
  }
  store.dispatch("saveAuction", { ...model.value }).then(({ data }) => {
    store.commit("notify", {
      type: "success",
      message: "The auction was successfully " + action,
    });
    router.push({
      name: "AuctionView",
      params: { id: data.data.id },
    });
  });
}

function deleteAuction() {
  if (
    confirm(
      `Are you sure you want to delete this auction? Operation can't be undone!!`
    )
  ) {
    store.dispatch("deleteAuction", model.value.id).then(() => {
      router.push({
        name: "Auctions",
      });
    });
  }
}

</script>

<style>
</style>
