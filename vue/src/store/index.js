import { createStore } from "vuex";
import axiosClient from "../axios";

const store = createStore({
  state: {
    user: {
      data: {},
      token: sessionStorage.getItem("TOKEN"),
    },
     auctions: {
      loading: false,
      links: [],
      data: []
    },
    currentAuction: {
      data: {},
      loading: false,
    },
    questionTypes: ["text"],
    notification: {
      show: false,
      type: 'success',
      message: ''
    }
  },
  getters: {},
  actions: {
    register({commit}, user) {
      return axiosClient.post('/register', user)
        .then((data) => {
          commit('setUser', data);
          return data;
        })
    },
    login({commit}, user) {
      return axiosClient.post('/login', user)
        .then(({data}) => {
          commit('setUser', data);
          return data;
        })
    },
    logout({commit}) {
      return axiosClient.post('/logout')
        .then(response => {
          commit('logout')
          return response;
        })
    },
    getUser({commit}) {
      return axiosClient.get('/user')
      .then(res => {
        console.log(res);
        commit('setUser', res.data)
      })
    },
    getAuctions({ commit }, {url = null} = {}) {
      commit('setAuctionsLoading', true)
      url = url || "/auction";
      return axiosClient.get(url).then((res) => {
        commit('setAuctionsLoading', false)
        commit("setAuctions", res.data);
        return res;
      });
    },
    getAuction({ commit }, id) {
      commit("setCurrentAuctionLoading", true);
      return axiosClient
        .get(`/auction/${id}`)
        .then((res) => {
          commit("setCurrentAuction", res.data);
          commit("setCurrentAuctionLoading", false);
          return res;
        })
        .catch((err) => {
          commit("setCurrentAuctionLoading", false);
          throw err;
        });
    },
    getAuctionBySlug({ commit }, slug) {
      commit("setCurrentAuctionLoading", true);
      return axiosClient
        .get(`/auction-by-slug/${slug}`)
        .then((res) => {
          commit("setCurrentAuction", res.data);
          commit("setCurrentAuctionLoading", false);
          return res;
        })
        .catch((err) => {
          commit("setCurrentAuctionLoading", false);
          throw err;
        });
    },
    saveAuction({ commit, dispatch }, auction) {

      delete auction.image_url;

      let response;
      if (auction.id) {
        response = axiosClient
          .put(`/auction/${auction.id}`, auction)
          .then((res) => {
            commit('setCurrentAuction', res.data)
            return res;
          });
      } else {
        response = axiosClient.post("/auction", auction).then((res) => {
          commit('setCurrentAuction', res.data)
          return res;
        });
      }

      return response;
    },
    deleteAuction({ dispatch }, id) {
      return axiosClient.delete(`/auction/${id}`).then((res) => {
        dispatch('getAuctions')
        return res;
      });
    },
  },
  mutations: {
    logout: (state) => {
      state.user.token = null;
      state.user.data = {};
      sessionStorage.removeItem("TOKEN");
    },
    setUser: (state, userData) => {
      state.user.token = userData.token;
      state.user.data = userData.user;
      sessionStorage.setItem('TOKEN', userData.token);
    },
    setToken: (state, token) => {
      state.user.token = token;
      sessionStorage.setItem('TOKEN', token);
    },
    setAuctionsLoading: (state, loading) => {
      state.auctions.loading = loading;
    },
    setAuctions: (state, auctions) => {
      state.auctions.links = auctions.meta.links;
      state.auctions.data = auctions.data;
    },
    setCurrentAuctionLoading: (state, loading) => {
      state.currentAuction.loading = loading;
    },
    setCurrentAuction: (state, auction) => {
      state.currentAuction.data = auction.data;
    },
    notify: (state, {message, type}) => {
      state.notification.show = true;
      state.notification.type = type;
      state.notification.message = message;
      setTimeout(() => {
        state.notification.show = false;
      }, 3000)
    },
  },
  modules: {},
});

export default store;
