<template>
    <main class="pt-5 bg-gray-400 h-screen fixed top-16 lg:w-52 md:w-40 sm:w-24">
        <jet-responsive-nav-link class="w-full" :href="route('home')">
            <div class="grid grid-cols-3 flex items-center">
                <img class="icon inline col-span-1" src="/open-iconic/svg/home.svg" alt="home icon">
                <p>Home</p>
            </div>
        </jet-responsive-nav-link>
        <div v-if="!$page.auth.user.loggedIn">
          <responsive-nav-link  :href="route('login')" @submit.prevent="login">
            <div class="grid grid-cols-3 flex items-center">
                <img class="icon inline col-span-1" src="/open-iconic/svg/account-login.svg" alt="login icon">
                <p>Login</p>
            </div>
          </responsive-nav-link>
          <responsive-nav-link href="/register">
            <div class="grid grid-cols-3 flex items-center">
                <img class="icon inline col-span-1" src="/open-iconic/svg/clipboard.svg" alt="register icon">
                <p class="col-span-2">Register</p>
            </div>
          </responsive-nav-link>
        </div>
        <div v-else>
            <form @submit.prevent="logout">
                <jet-responsive-nav-link class="object-top" as="button">
                    <div class="grid grid-cols-3 flex items-center">
                        <img class="icon inline col-span-1" src="/open-iconic/svg/account-logout.svg" alt="logout icon">
                        <p class="col-span-2">Logout</p>
                    </div>
                </jet-responsive-nav-link>
            </form>
            <hr>
            <jet-responsive-nav-link href="/channel">
                <div class="grid grid-cols-3 flex items-center">
                    <img class="icon inline col-span-1" src="/open-iconic/svg/data-transfer-upload.svg" alt="upload icon">
                    <p class="col-span-2">My Videos</p>
                </div>
            </jet-responsive-nav-link>       
        </div>
        <div class="h-full relative">
            <jet-responsive-nav-link href="/about" class="absolute top-0 w-full">
                <div class="grid grid-cols-3 flex items-center">
                    <img class="icon inline col-span-1" src="/open-iconic/svg/question-mark.svg" alt="question mark icon">
                    <p>About</p>
                </div>
            </jet-responsive-nav-link> 
        </div>   
    </main>
</template>

<script>
    import JetNavLink from '@/Jetstream/NavLink'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'
    import ResponsiveNavLink from '@/Jetstream/NormalResponsiveNavLink'

  export default {
    components: {
        JetNavLink,
        JetResponsiveNavLink,
        ResponsiveNavLink
    },
    methods: {
       
        logout() {
            axios.post(route('logout').url()).then(response => {
                window.location = '/';
            })
        }
    }
  }
</script>