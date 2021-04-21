<template>
    <div class="h-screen fixed sm:top-16 top-24 lg:w-52 md:w-40 sm:w-24 z-20 nav-header border-r border-black overflow-y-auto">
        <jet-responsive-nav-link class="w-full" :href="route('home')">
            <div class="grid grid-cols-3 flex items-center">
                <img class="inline col-span-1" width="16px" height="16px" src="/open-iconic/svg/home.svg" alt="home icon" aria-hidden="true">
                <p>Home</p>
            </div>
        </jet-responsive-nav-link>
        <div v-if="!$page.props.auth.user.loggedIn">
          <jet-responsive-nav-link  :href="route('login')">
            <div class="grid grid-cols-3 flex items-center">
                <img class="inline col-span-1" width="16px" height="16px" src="/open-iconic/svg/account-login.svg" alt="login icon" aria-hidden="true">
                <p>Login</p>
            </div>
          </jet-responsive-nav-link>
          <jet-responsive-nav-link :href="route('register')">
            <div class="grid grid-cols-3 flex items-center">
                <img class="inline col-span-1" width="16px" height="16px" src="/open-iconic/svg/clipboard.svg" alt="register icon" aria-hidden="true">
                <p class="col-span-2">Register</p>
            </div>
          </jet-responsive-nav-link>
        </div>
        <div v-else>
            <jet-responsive-nav-link :href="route('account')">
                <div class="grid grid-cols-3 flex items-center">
                    <img class="inline col-span-1" width="16px" height="16px" src="/open-iconic/svg/person.svg" alt="account icon" aria-hidden="true">
                    <p class="col-span-2">Account</p>
                </div>
          </jet-responsive-nav-link>
            <form @submit.prevent="logout" aria-label="logout">
                <jet-responsive-nav-link class="object-top" as="button">
                    <div class="grid grid-cols-3 flex items-center">
                        <img class="inline col-span-1" width="16px" height="16px" src="/open-iconic/svg/account-logout.svg" alt="logout icon" aria-hidden="true">
                        <p class="col-span-2">Logout</p>
                    </div>
                </jet-responsive-nav-link>
            </form>
            <hr>
            <jet-responsive-nav-link href="/channel">
                <div class="grid grid-cols-3 flex items-center">
                    <img class="inline col-span-1" width="16px" height="16px" src="/open-iconic/svg/data-transfer-upload.svg" alt="upload icon" aria-hidden="true">
                    <p class="col-span-2">My Videos</p>
                </div>
            </jet-responsive-nav-link>  
            <div>
                <jet-responsive-nav-link :href="route('history')" class="w-full">
                    <div class="grid grid-cols-3 flex items-center">
                        <img class="inline col-span-1" width="16px" height="16px" src="/open-iconic/svg/timer.svg" alt="timer icon" aria-hidden="true">
                        <p>History</p>
                    </div>
                </jet-responsive-nav-link> 
            </div>      
        </div>
        <div>
            <jet-responsive-nav-link :href="route('changelog')" class="w-full">
                <div class="grid grid-cols-3 flex items-center">
                    <img class="inline col-span-1" width="16px" height="16px" src="/open-iconic/svg/code.svg" alt="changelog icon" aria-hidden="true">
                    <p>Changelog</p>
                </div>
            </jet-responsive-nav-link> 
        </div>  
        <div>
            <jet-responsive-nav-link href="/about" class="w-full">
                <div class="grid grid-cols-3 flex items-center">
                    <img class="inline col-span-1" width="16px" height="16px" src="/open-iconic/svg/question-mark.svg" alt="question mark icon" aria-hidden="true">
                    <p>About</p>
                </div>
            </jet-responsive-nav-link> 
        </div>   
        <div>
            <jet-responsive-nav-link href="/contact" class="w-full">
                <div class="grid grid-cols-3 flex items-center">
                    <img class="inline col-span-1" width="16px" height="16px" src="/open-iconic/svg/chat.svg" alt="contact icon" aria-hidden="true">
                    <p>Contact</p>
                </div>
            </jet-responsive-nav-link> 
        </div> 
    </div>
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