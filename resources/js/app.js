require('./bootstrap');

require('moment');

import Vue from 'vue';
import Vuex from 'vuex';

import { InertiaApp } from '@inertiajs/inertia-vue';
import PortalVue from 'portal-vue';

Vue.mixin({ methods: { route } });
Vue.use(InertiaApp);
Vue.use(PortalVue);
Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
      isHidden: false
    },
    mutations: {
      flip (state) {
        state.isHidden = !state.isHidden
      },
      setHidden(state) {
          state.isHidden = true
      },
      setNotHidden(state) {
          state.isHidden = false
      }
    }
  })

const app = document.getElementById('app');

new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
            data() {
                return {
                    isHidden: false
                }
            }
        }),
    store
}).$mount(app);
