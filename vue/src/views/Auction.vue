<template>
  <PageComponent>
    <template v-slot:header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Auctions</h1>
          <router-link
          :to="{name: 'AuctionCreate'}"
          class="
          py-2
          px-3
          text-white
          bg-emerald-500
          rounded-md
          hover:bg-emerald-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 -mt-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
</svg>
          Add an Auction
        </router-link>
      </div>
    </template>
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3">
      <div
          v-for="auction in auctions"
          :key="auction.id"
          class="
          flex flex-col
          py-4
          px-6
          shadow-md
          bg-white
          hover:bg-gray-50
          h-[470px]
          " 
        >
        <img :src="auction.image" alt="" class="w-full h-48 object-cover" />
        <h4 class="mt-4 text-lg font-bold">{{ auction.title }}</h4>
        <div v-html="auction.description" class="overflow-hidden flex-1"></div>
        <div class="flex justify-between items-center mt-3">
          <router-link
          :to="{ name: 'AuctionView', params: { id: auction.id } }"
          class="
          flex flex-col
          py-2
          px-4
          border border-transparent
          text-sm
          rounded-md
          text-white
          bg-indigo-600
          hover:bg-indigo-700
          focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
          "
          >
<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
</svg>
          Edit
          </router-link>
          <button v-if="auction.id"
          type="button"
          @click="deleteAuction(auction)"
          class="
          h-8
          w-8
          flex
          items-center
          rounded-full
          border border-transparent
          text-sm text-red-500
          focus:ring-2 focus:ring-offset-2 focus:ring-red-500
          "
          >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
          </button>
        </div>
        </div>
        </div>
  </PageComponent>

</template>

<script setup>
import store from "../store";
import { computed } from "vue";
import PageComponent from "../components/PageComponent.vue";

const surveys = computed(() => store.state.auctions);

store.dispatch("getAuctions");

function deleteAuction(auction) {
  if (
    confirm(
      `Are you sure you want to delete this auction? Operation can't be undone!!`
    )
  ) {
    store.dispatch("deleteAuction", survey.id).then(() => {
      store.dispatch("getAuctions");
    });
  }
}

</script>