<template>
    <header-layout>
        <article class="flex flex-col">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight self-center">
                Profile
            </h1>
            <div>
                <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                    <div>
                        <update-profile-information-form :user="$page.props.user" />

                        <jet-section-border />
                    </div>

                    <div>
                        <update-password-form class="mt-10 sm:mt-0" />

                        <jet-section-border />
                    </div>

                    <!-- <div v-if="$page.jetstream.canManageTwoFactorAuthentication">
                        <two-factor-authentication-form class="mt-10 sm:mt-0" />

                        <jet-section-border />
                    </div> -->

                    <!-- <logout-other-browser-sessions-form :sessions="sessions" class="mt-10 sm:mt-0" /> -->

                    <!-- <jet-section-border /> -->

                    <delete-user-form class="mt-10 sm:mt-0" />
                </div>
            </div>
        </article>
    </header-layout>
</template>

<script>
    import HeaderLayout from '@/Layouts/HeaderLayout'
    import DeleteUserForm from './DeleteUserForm'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import LogoutOtherBrowserSessionsForm from './LogoutOtherBrowserSessionsForm'
    import TwoFactorAuthenticationForm from './TwoFactorAuthenticationForm'
    import UpdatePasswordForm from './UpdatePasswordForm'
    import UpdateProfileInformationForm from './UpdateProfileInformationForm'

    export default {
        props: ['sessions'],

        components: {
            HeaderLayout,
            DeleteUserForm,
            JetSectionBorder,
            LogoutOtherBrowserSessionsForm,
            TwoFactorAuthenticationForm,
            UpdatePasswordForm,
            UpdateProfileInformationForm,
        },
    }
</script>
