<template>
    <main class="pt-5 bg-gray-400 h-screen fixed top-16 lg:w-52 md:w-40 sm:w-24">
        <jet-responsive-nav-link class="w-full" href="/">
            <div class="grid grid-cols-3 flex items-center">
                <img class="icon inline grid-span-1" src="/open-iconic/svg/home.svg" alt="home icon">
                <p>Home</p>
            </div>
        </jet-responsive-nav-link>
        <div v-if="!$page.auth.user.loggedIn">
          <jet-responsive-nav-link  href="/login">
            <div class="grid grid-cols-3 flex items-center">
                <img class="icon inline grid-span-1" src="/open-iconic/svg/account-login.svg" alt="login icon">
                <p>Login</p>
            </div>
          </jet-responsive-nav-link>
          <jet-responsive-nav-link href="/register">
            <div class="grid grid-cols-3 flex items-center">
                <img class="icon inline grid-span-1" src="/open-iconic/svg/clipboard.svg" alt="register icon">
                <p>Register</p>
            </div>
          </jet-responsive-nav-link>
        </div>
        <div v-else>
            <form @submit.prevent="logout">
                <jet-responsive-nav-link class="object-top" as="button">
                    <div class="grid grid-cols-3 flex items-center">
                        <img class="icon inline grid-span-1" src="/open-iconic/svg/account-logout.svg" alt="logout icon">
                        <p>Logout</p>
                    </div>
                </jet-responsive-nav-link>
            </form>
            <hr>
            <jet-responsive-nav-link href="/upload">
                <div class="grid grid-cols-3 flex items-center">
                    <img class="icon inline grid-span-1" src="/open-iconic/svg/data-transfer-upload.svg" alt="upload icon">
                    <p>Upload</p>
                </div>
            </jet-responsive-nav-link>    
            
        </div>
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