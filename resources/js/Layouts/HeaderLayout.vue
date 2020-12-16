<template>
  <main class="h-full"> 
    <header class="grid lg:grid-cols-8 md:grid-cols-6 grid-cols-2 lg:gap-4 bg-gray-700 fixed top-0 w-full h-16 z-50">
        <div class="flex flex-row items-center justify-center">
          <button @click="nav">
            <img class="icon" src="/open-iconic/svg/menu.svg">
          </button>
          <jet-responsive-nav-link href="/">Home</jet-responsive-nav-link>
        </div>
        
        <form class="sm:visible invisible 
        flex items-center justify-center 
        lg:col-span-2 md:col-span-2 sm:col-span-1 lg:col-start-7 md:col-start-5">
          <input class="w-4/5 h-6" type="text" name="search">
          <button class="icon-button h-6 w-8 flex items-center justify-center bg-gray-600">
            <img class="icon" src="/open-iconic/svg/magnifying-glass.svg">
          </button>
        </form>

    </header>
    <article class="h-full pt-20">
      <div v-bind:class="{ hidden: isHidden}">
        <sidebar/>
      </div>
      <div class="h-auto" v-bind:class="{ 
        'w-full': isHidden, 
        'lg:ml-52 md:ml-40 sm:ml-24': !isHidden
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
        isHidden: false
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
          this.isHidden = !this.isHidden;
        }
    }
  }
</script>