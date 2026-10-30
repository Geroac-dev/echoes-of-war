import { createStore } from "vuex";
import users from "./users";
import snackbar from "./snackbar";


const store = createStore({
    modules: {
      users,
      snackbar,
    },
});
export default store;
