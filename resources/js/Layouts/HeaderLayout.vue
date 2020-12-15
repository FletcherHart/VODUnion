<template>
  <main class="h-full"> 
    <header class="flex justify-between pl-5 pr-5 pt-5 bg-gray-700 fixed w-full h-16">
        <jet-responsive-nav-link href="/">Home</jet-responsive-nav-link>
        <button @click="nav">
          Nav
        </button>
        <form class="inline">
          <input type="text" name="search">
        </form>
    </header>
    <article class="h-full">
      <div v-bind:class="{ hidden: isActive}">
        <sidebar/>
      </div>
      <div v-bind:class="{ 
        'w-full': isWide, 
        'lg:ml-52 md:ml-40 sm:ml-24': !isWide 
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
import Button from '../Jetstream/Button.vue'
  export default {
    components: {
        JetNavLink,
        JetResponsiveNavLink,
        Sidebar
    },
    data() {
      return{
        isActive: false,
        isWide: false
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
          this.isActive = !this.isActive;
          this.isWide = !this.isWide;
        }
    }
  }
</script>