<template>
  <main class="h-full"> 
    <header class="grid lg:grid-cols-8 md:grid-cols-6 grid-cols-2 lg:gap-4 bg-gray-700 fixed top-0 w-full h-16 z-30">
        <div class="flex flex-row items-center justify-center">
          <button @click="nav">
            <img class="icon" src="/open-iconic/svg/menu.svg">
          </button>
          <jet-responsive-nav-link href="/">Home</jet-responsive-nav-link>
        </div>
        
        <form class="sm:visible invisible 
        flex items-center justify-center 
        lg:col-span-2 md:col-span-2 sm:col-span-1 lg:col-start-4 md:col-start-2" @submit.prevent="search">
          <input class="w-4/5 h-6" type="text" id="search">
          <button class="icon-button h-6 w-8 flex items-center justify-center bg-gray-600">
            <img class="icon" src="/open-iconic/svg/magnifying-glass.svg">
          </button>
        </form>

        <div class="flex items-center text-white font-medium lg:col-start-7 md:col-start-5 col-span-2">
            <div>Have questions? Email 
              <a href="mailto:support@vodunion.com">support@vodunion.com</a>
            </div>
        </div>

    </header>
    <article class="h-full pt-20">
      <div v-bind:class="{ hidden: this.$store.state.isHidden}">
        <sidebar/>
      </div>
      <div class="h-auto" v-bind:class="{ 
        'w-full': this.$store.state.isHidden, 
        'lg:ml-52 md:ml-40 sm:ml-24': !this.$store.state.isHidden
      }">
        <slot />
      </div>
    </article>
  </main>
</template>

<script>
    import JetNavLink from '@/Jetstream/NavLink'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'
    import Sidebar from '@/Layouts/Sidebar'
  export default {
    components: {
        JetNavLink,
        JetResponsiveNavLink,
        Sidebar
    },
    data() {
      return{
        windowWidth: window.innerWidth
      }
    },
    methods: {
        logout() {
            axios.post(route('logout').url()).then(response => {
                window.location = '/';
            })
        },
        login() {
            axios.post(route('login').url()).then(response => {
                window.location = '/login';
            })
        },
        nav() {
          this.$store.commit('flip');
        },
        onResize() {
          this.windowWidth = window.innerWidth
        },
        search() {
          var term = document.getElementById('search').value;
          if(term != "") {
            this.$inertia.get('/search/'+term);
          }
        }
    },
    beforeMount() {
      if(this.windowWidth < 640) {
        this.$store.commit('setHidden');
      } else {
        this.$store.commit('setNotHidden');
      }
    },
    mounted() {
      this.$nextTick(() => {
        window.addEventListener('resize', this.onResize);
      })
    },
    beforeDestroy() { 
      window.removeEventListener('resize', this.onResize); 
    },
    watch: {
      windowWidth(newWidth, oldWidth) {
        if(newWidth < 640) {
          this.$store.commit('setHidden');
        }
      }
    },
  }
</script>