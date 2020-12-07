<template>
  <div class="py-12">
      <div v-if="$page.auth.user.loggedIn">
        <form @submit.prevent="submit">
          <input class="border-solid border-2 border-black-600" id="text" type="textarea" v-model="form.text" placeholder="Comment" />
          <button type="submit">Submit</button>
        </form>
      </div>
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
              <div v-for="comment in $page.comments" :key="comment.id">
                <div class="my-3">
                  <a class="inline text-blue-600 hover:text-purple-600 mb-3" :href="'user/' + comment.user_id">{{comment.name}}</a>
                  <p class="inline">Date Posted {{comment.date}}</p>
                  <p>{{comment.text}}</p>
                </div>
                <hr>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        text:null
      },
    }
  },
  methods: {
    submit() {
      this.$inertia.post('/video/' + this.$page.data.id + "/comment", this.form)
    },
  }
}
</script>