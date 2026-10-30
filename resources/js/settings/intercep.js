
import axios from 'axios';
import vuex from "vuex";
import store from "../stores";

axios.interceptors.response.use(
    (response) => {
        return response;
      },
      async (error) => {
        const config = error.config;
        if (
          error.response.status === 403 || error.response.status === 419
        ) {
            store.dispatch("users/setIsOpenMessage", true);
        }
        
        return Promise.reject(error);
      }
);

export default axios;