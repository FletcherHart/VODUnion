<template>
    <main class="pl-5 pr-5 pt-5 bg-gray-400 h-screen">
        <jet-responsive-nav-link href="/">Home</jet-responsive-nav-link>
        <div v-if="!$page.auth.user.loggedIn">
          <jet-responsive-nav-link  href="/login">Login</jet-responsive-nav-link>
          <jet-responsive-nav-link href="/register">Register</jet-responsive-nav-link>
        </div>
        <form v-else @submit.prevent="logout">
            <jet-responsive-nav-link class="object-top" as="button">
                Logout
            </jet-responsive-nav-link>
        </form>
    </main>
</template>

<script>
    import JetNavLink from '@/Jetstream/NavLink'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'
  export default {
    components: {
        JetNavLink,
        JetResponsiveNavLink,
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
        }
    }
  }
</script>