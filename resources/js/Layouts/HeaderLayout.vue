<template>
  <div class="h-full"> 
    <header class="fixed top-0 w-full h-24 sm:h-16 z-30 nav-header">
      <div class="flex flex-col h-full">
        <div class="flex justify-between sm:h-full h-16 mr-10">
          <div class="flex flex-row items-center justify-center ml-6">
            <button @click="nav" class="hover:bg-gray-600 p-2 rounded w-10 flex justify-center" aria-label="display menu">
              <svg class="inline col-span-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 8 8">
                  <path d="M0 0v1h8v-1h-8zm0 2.97v1h8v-1h-8zm0 3v1h8v-1h-8z" transform="translate(0 1)" />
              </svg>
            </button>
            <inertia-link href="/" class="block pl-3 pr-4 py-2 ml-5 font-semibold rounded hover:rounded text-black" style="background-color:#7DAEFC;">VODUnion</inertia-link>
          </div>
          
          <form class="flex items-center justify-center" @submit.prevent="search" v-if="this.windowWidth > 640">
            <input class="w-96 h-8 border-black border p-1" type="text" id="search" placeholder="Search" aria-label="Search for videos">
            <button class="rounded-r icon-button h-8 w-8 flex items-center justify-center bg-gray-600" type="submit" aria-label="submit">
              <svg class="inline col-span-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 8 8">
                  <path d="M3.5 0c-1.93 0-3.5 1.57-3.5 3.5s1.57 3.5 3.5 3.5c.59 0 1.17-.14 1.66-.41a1 1 0 0 0 .13.13l1 1a1.02 1.02 0 1 0 1.44-1.44l-1-1a1 1 0 0 0-.16-.13c.27-.49.44-1.06.44-1.66 0-1.93-1.57-3.5-3.5-3.5zm0 1c1.39 0 2.5 1.11 2.5 2.5 0 .66-.24 1.27-.66 1.72-.01.01-.02.02-.03.03a1 1 0 0 0-.13.13c-.44.4-1.04.63-1.69.63-1.39 0-2.5-1.11-2.5-2.5s1.11-2.5 2.5-2.5z"
                  />
              </svg>
            </button>
          </form>
        </div>
        <form class="shadow-md flex items-center justify-center fixed top-16 w-full m-b-8" @submit.prevent="search" v-if="this.windowWidth < 640">
          <input class="rounded rounded-r-none w-full h-8 p-1" type="text" id="search" placeholder="Search" aria-label="Search for videos">
          <button class="rounded-r icon-button h-8 w-8 flex items-center justify-center bg-gray-600" aria-label="submit">
            <svg class="inline col-span-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 8 8">
                <path d="M3.5 0c-1.93 0-3.5 1.57-3.5 3.5s1.57 3.5 3.5 3.5c.59 0 1.17-.14 1.66-.41a1 1 0 0 0 .13.13l1 1a1.02 1.02 0 1 0 1.44-1.44l-1-1a1 1 0 0 0-.16-.13c.27-.49.44-1.06.44-1.66 0-1.93-1.57-3.5-3.5-3.5zm0 1c1.39 0 2.5 1.11 2.5 2.5 0 .66-.24 1.27-.66 1.72-.01.01-.02.02-.03.03a1 1 0 0 0-.13.13c-.44.4-1.04.63-1.69.63-1.39 0-2.5-1.11-2.5-2.5s1.11-2.5 2.5-2.5z"
                />
            </svg>
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