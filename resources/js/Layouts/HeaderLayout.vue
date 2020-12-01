<template>
  <main>
    <header>
        <inertia-link href="/">Home</inertia-link>
        <inertia-link v-if="!$page.auth.user.loggedIn" href="/login">Login</inertia-link>
        <form v-else @submit.prevent="logout">
            <jet-responsive-nav-link as="button">
                Logout
            </jet-responsive-nav-link>
        </form>
    </header>
    <article>
      <slot />
    </article>
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