<template>
  <div class="h-full"> 
    <header class="fixed top-0 w-full h-24 sm:h-16 z-30 nav-header">
      <div class="flex flex-col h-full">
        <div class="flex justify-between sm:h-full h-16">
          <div class="flex flex-row items-center justify-center ml-6">
            <button @click="nav" class="hover:bg-gray-600 p-2 rounded focus:outline-none w-10 flex justify-center" aria-label="display menu">
              <img width="16px" height="16px" src="/open-iconic/svg/menu.svg" alt="menu icon" aria-hidden="true">
            </button>
            <a href="/" class="block pl-3 pr-4 py-2 ml-5 font-semibold rounded hover:rounded text-black" style="background-color:#7DAEFC;">VODUnion</a>
          </div>
          
          <form class="flex items-center justify-center" @submit.prevent="search" v-if="this.windowWidth > 640">
            <input class="w-96 h-8 border-black border p-1" type="text" id="search" placeholder="Search" aria-label="Search for videos">
            <button class="rounded-r icon-button h-8 w-8 flex items-center justify-center bg-gray-600" type="submit" aria-label="submit">
              <img width="16px" height="16px" src="/open-iconic/svg/magnifying-glass.svg" alt="search icon" aria-hidden="true">
            </button>
          </form>

          <div class="flex items-center font-medium mr-6">
              <div class="text-center">Have questions? Email 
                <a class="underline" href="mailto:support@vodunion.com">support@vodunion.com</a>
              </div>
          </div>
        </div>
        <form class="shadow-md flex items-center justify-center fixed top-16 w-full m-b-8" @submit.prevent="search" v-if="this.windowWidth < 640">
          <input class="rounded rounded-r-none w-full h-8 p-1" type="text" id="search" placeholder="Search" aria-label="Search for videos">
          <button class="rounded-r icon-button h-8 w-8 flex items-center justify-center bg-gray-600" aria-label="submit">
            <img width="16px" height="16px" src="/open-iconic/svg/magnifying-glass.svg" alt="search icon" aria-hidden="true">
          </button>
        </form>
      </div>

    </header>
    <nav v-bind:class="{ hidden: this.$store.state.isHidden}">
      <sidebar/>
    </nav>
    <main class="overflow-auto border-t border-black relative top-16" v-bind:class="{'pt-10': (this,windowWidth < 640)}">
      <div class="h-auto" v-bind:class="{ 
        'w-full': this.$store.state.isHidden, 
        'lg:ml-52 md:ml-40 sm:ml-24': !this.$store.state.isHidden
      }">
        <slot />
      </div>
    </main>
  </div>
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