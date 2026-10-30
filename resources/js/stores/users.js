import axios from "axios";

const state = {
    user: null,
};

const types = {
    SET_USER: "SET_USER",
};


const actions = {
    setUser({ commit }) {
        axios
            .get("/get-user")
            .then(({ data }) => {
                commit(types.SET_USER, data.user);
            });
    },
    logoutUser({ commit }) {
        commit(types.SET_USER, null);
    },
};

const mutations = {
    [types.SET_USER](state, user) {
        state.user = user;
    },
};

export default {
    namespaced: true,
    state,
    actions,
    mutations
};
