<template>
  <main class="h-full"> 
    <header class="shadow-md  bg-gray-700 fixed top-0 w-full h-24 sm:h-16 z-30">
      <div class="flex flex-col h-full">
        <div class="flex justify-between sm:h-full h-16">
          <div class="flex flex-row items-center justify-center ml-6">
            <button @click="nav" class="hover:bg-white p-2 rounded focus:outline-none w-10 flex justify-center">
              <img class="icon" src="/open-iconic/svg/menu.svg">
            </button>
            <jet-responsive-nav-link href="/">Home</jet-responsive-nav-link>
          </div>
          
          <form class="flex items-center justify-center" @submit.prevent="search" v-if="this.windowWidth > 640">
            <input class="rounded rounded-r-none w-96 h-8" type="text" id="search">
            <button class="rounded-r icon-button h-8 w-8 flex items-center justify-center bg-gray-600">
              <img class="icon" src="/open-iconic/svg/magnifying-glass.svg">
            </button>
          </form>

          <div class="flex items-center text-white font-medium mr-6">
              <div class="text-center">Have questions? Email 
                <a class="hover:bg-white hover:text-black" href="mailto:support@vodunion.com">support@vodunion.com</a>
              </div>
          </div>
        </div>
        <form class="shadow-md flex items-center justify-center fixed top-16 w-full m-b-8" @submit.prevent="search" v-if="this.windowWidth < 640">
          <input class="rounded rounded-r-none w-full h-8" type="text" id="search">
          <button class="rounded-r icon-button h-8 w-8 flex items-center justify-center bg-gray-600">
            <img class="icon" src="/open-iconic/svg/magnifying-glass.svg">
          </button>
        </form>
      </div>

    </header>
    <article class="h-full" v-bind:class="{ 'pt-20': (this.windowWidth > 640), 'pt-28': (this,windowWidth < 640)}">
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