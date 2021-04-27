<template>
    <div class="h-screen fixed sm:top-16 top-24 lg:w-52 md:w-40 sm:w-24 z-20 nav-header border-r border-black overflow-y-auto">
        <jet-responsive-nav-link class="w-full" :href="route('home')">
            <div class="grid grid-cols-3 flex items-center">
                <!-- <img  width="16px" height="16px" src="/open-iconic/svg/home.svg" alt="home icon" aria-hidden="true"> -->
                <svg class="inline col-span-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 8 8">
                    <path d="M4 0l-4 3h1v4h2v-2h2v2h2v-4.03l1 .03-4-3z" />
                </svg>
                <p>Home</p>
            </div>
        </jet-responsive-nav-link>
        <div v-if="!$page.props.auth.user.loggedIn">
          <jet-responsive-nav-link  :href="route('login')">
            <div class="grid grid-cols-3 flex items-center">
                <svg class="inline col-span-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 8 8">
                    <path d="M3 0v1h4v5h-4v1h5v-7h-5zm1 2v1h-4v1h4v1l2-1.5-2-1.5z" />
                </svg>
                <p>Login</p>
            </div>
          </jet-responsive-nav-link>
          <jet-responsive-nav-link :href="route('register')">
            <div class="grid grid-cols-3 flex items-center">
                <svg class="inline col-span-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 8 8">
                    <path d="M3.5 0c-.28 0-.5.22-.5.5v.5h-.75c-.14 0-.25.11-.25.25v.75h3v-.75c0-.14-.11-.25-.25-.25h-.75v-.5c0-.28-.22-.5-.5-.5zm-3.25 1c-.14 0-.25.11-.25.25v6.5c0 .14.11.25.25.25h6.5c.14 0 .25-.11.25-.25v-6.5c0-.14-.11-.25-.25-.25h-.75v2h-5v-2h-.75z" />
                </svg>
                <p class="col-span-2">Register</p>
            </div>
          </jet-responsive-nav-link>
        </div>
        <div v-else>
            <jet-responsive-nav-link :href="route('account')">
                <div class="grid grid-cols-3 flex items-center">
                    <svg class="inline col-span-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 8 8">
                        <path d="M4 0c-1.1 0-2 1.12-2 2.5s.9 2.5 2 2.5 2-1.12 2-2.5-.9-2.5-2-2.5zm-2.09 5c-1.06.05-1.91.92-1.91 2v1h8v-1c0-1.08-.84-1.95-1.91-2-.54.61-1.28 1-2.09 1-.81 0-1.55-.39-2.09-1z" />
                    </svg>
                    <p class="col-span-2">Account</p>
                </div>
          </jet-responsive-nav-link>
            <form @submit.prevent="logout" aria-label="logout">
                <jet-responsive-nav-link class="object-top" as="button">
                    <div class="grid grid-cols-3 flex items-center">
                        <svg class="inline col-span-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 8 8">
                            <path d="M3 0v1h4v5h-4v1h5v-7h-5zm-1 2l-2 1.5 2 1.5v-1h4v-1h-4v-1z" />                        
                        </svg>
                        <p class="col-span-2">Logout</p>
                    </div>
                </jet-responsive-nav-link>
            </form>
            <hr>
            <jet-responsive-nav-link href="/channel">
                <div class="grid grid-cols-3 flex items-center">
                    <svg class="inline col-span-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 8 8">
                        <path d="M0 0v1h8v-1h-8zm4 2l-3 3h2v3h2v-3h2l-3-3z" />                        
                    </svg>                    
                    <p class="col-span-2">My Videos</p>
                </div>
            </jet-responsive-nav-link>  
            <div>
                <jet-responsive-nav-link :href="route('history')" class="w-full">
                    <div class="grid grid-cols-3 flex items-center">
                        <svg class="inline col-span-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 8 8">
                            <path d="M2 0v1h1v.03c-1.7.24-3 1.71-3 3.47 0 1.93 1.57 3.5 3.5 3.5s3.5-1.57 3.5-3.5c0-.45-.1-.87-.25-1.25l-.91.38c.11.29.16.57.16.88 0 1.39-1.11 2.5-2.5 2.5s-2.5-1.11-2.5-2.5 1.11-2.5 2.5-2.5c.3 0 .59.05.88.16l.34-.94c-.23-.08-.47-.12-.72-.16v-.06h1v-1h-3zm5 1.16s-3.65 2.81-3.84 3c-.19.2-.19.49 0 .69.19.2.49.2.69 0 .2-.2 3.16-3.69 3.16-3.69z"
                            />                        
                        </svg>
                        <p>History</p>
                    </div>
                </jet-responsive-nav-link> 
            </div>      
        </div>
        <div>
            <jet-responsive-nav-link :href="route('changelog')" class="w-full">
                <div class="grid grid-cols-3 flex items-center">
                    <svg class="inline col-span-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 8 8">
                        <path d="M5 0l-3 6h1l3-6h-1zm-4 1l-1 2 1 2h1l-1-2 1-2h-1zm5 0l1 2-1 2h1l1-2-1-2h-1z" transform="translate(0 1)" />                      
                    </svg> 
                    <p>Changelog</p>
                </div>
            </jet-responsive-nav-link> 
        </div>  
        <div>
            <jet-responsive-nav-link href="/about" class="w-full">
                <div class="grid grid-cols-3 flex items-center">
                    <svg class="inline col-span-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 8 8">
                        <path d="M2.47 0c-.85 0-1.48.26-1.88.66-.4.4-.54.9-.59 1.28l1 .13c.04-.25.12-.5.31-.69.19-.19.49-.38 1.16-.38.66 0 1.02.16 1.22.34.2.18.28.4.28.66 0 .83-.34 1.06-.84 1.5-.5.44-1.16 1.08-1.16 2.25v.25h1v-.25c0-.83.31-1.06.81-1.5.5-.44 1.19-1.08 1.19-2.25 0-.48-.17-1.02-.59-1.41-.43-.39-1.07-.59-1.91-.59zm-.5 7v1h1v-1h-1z"
                        transform="translate(2)" />
                    </svg>
                    <p>About</p>
                </div>
            </jet-responsive-nav-link> 
        </div>   
        <div>
            <jet-responsive-nav-link href="/contact" class="w-full">
                <div class="grid grid-cols-3 flex items-center">
                    <svg class="inline col-span-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 8 8">
                        <path d="M0 0v5l1-1h1v-3h3v-1h-5zm3 2v4h4l1 1v-5h-5z" />
                    </svg>
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