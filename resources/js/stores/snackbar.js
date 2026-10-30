const state = {
    snackbar: null,
    color: null,
    text: null,
};

const types = {
    SET_SNACKBAR: "SET_SNACKBAR",
    SET_COLOR: "SET_COLOR",
    SET_TEXT: "SET_TEXT",
};

const actions = {
    setSnackbar({ commit }, val) {
        commit(types.SET_COLOR, val.color);
        commit(types.SET_TEXT, val.text);
        commit(types.SET_SNACKBAR, false);
        commit(types.SET_SNACKBAR, true);
        setTimeout(() => {
            commit(types.SET_SNACKBAR, false);
        }, 5000);
    },
};

const mutations = {
    [types.SET_SNACKBAR](state, snackbar) {
        state.snackbar = snackbar;
    },
    [types.SET_COLOR](state, color) {
        state.color = color;
    },
    [types.SET_TEXT](state, text) {
        state.text = text;
    },
};

export default {
    namespaced: true,
    state,
    actions,
    mutations
};
