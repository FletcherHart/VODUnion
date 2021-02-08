<template>
    <jet-action-section>
        <template #title>
            Delete Account
        </template>

        <template #description>
            Permanently delete your account.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain, then enter your password to confirm you would like to permanently delete your account.
            </div>
            <div class="mt-4">
                <jet-input type="password" class="mt-1 block w-3/4" placeholder="Password"
                            ref="password"
                            v-model="form.password"
                            @keyup.enter.native="deleteUser" />
                <jet-input-error v-if="form.errors.password" :message="form.errors['password']" class="mt-2" />
            </div>
            <jet-danger-button class="ml-2" @click.native="deleteUser" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Delete Account
            </jet-danger-button>
        </template>
    </jet-action-section>
</template>

<script>
    import JetActionSection from '@/Jetstream/ActionSection'
    import JetDialogModal from '@/Jetstream/DialogModal'
    import JetDangerButton from '@/Jetstream/DangerButton'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'

    export default {
        components: {
            JetActionSection,
            JetDangerButton,
            JetDialogModal,
            JetInput,
            JetInputError,
            JetSecondaryButton,
        },

        data() {
            return {
                confirmingUserDeletion: false,

                form: this.$inertia.form({
                    '_method': 'DELETE',
                    password: '',
                }, {
                    bag: 'deleteUser'
                })
            }
        },

        methods: {
            confirmUserDeletion() {
                this.form.password = '';

                this.confirmingUserDeletion = true;

                setTimeout(() => {
                    console.log(this.$refs.password)
                    this.$refs.password.focus()
                }, 250)
            },

            deleteUser() {
                this.form.post(route('current-user.destroy'), {
                    preserveScroll: true
                });
            },
        },
    }
</script>
