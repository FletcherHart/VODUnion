<template>
  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
              <div v-if="$page.props.auth.user.loggedIn">
              <form @submit.prevent="submit">
                <input class="border-solid border-2 border-black-600" id="text" type="textarea" v-model="form.text" placeholder="Comment" />
                <button type="submit">Submit</button>
              </form>
            </div>
            <div v-else> 
              Please login to post a comment.
            </div>
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
              <div v-for="comment in $page.props.comments" :key="comment.id">
                <div class="my-3">
                  <!-- <a class="inline text-blue-600 hover:text-purple-600 mb-3" :href="'user/' + comment.user_id">{{comment.name}}</a> -->
                  <div class="flex justify-between">
                    <div>
                      <p class="inline">{{comment.name}}</p>
                      <p class="inline">Date Posted {{comment.date}}</p>
                    </div>
                    <div v-if="$page.props.auth.user.loggedIn">
                      <button class="bg-red-600 text-white rounded ml-2 mr-2"
                      v-on:click="deleteComment(comment.id)"
                      v-if="$page.props.user.id == comment.user_id || $page.props.user.role_id == 4 || $page.props.user.id == ownerID">Delete</button>
                     </div>
                  </div>
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
  props: {
    id: Number,
    ownerID: Number
  },
  data() {
    return {
      form: {
        text:null
      },
    }
  },
  methods: {
    submit() {
      this.$inertia.post('/video/' + this.id + "/comment", this.form, {preserveScroll:true});
      this.form.text = null;
    },
    deleteComment(id) {
      this.$inertia.delete('/comment/'+id, {preserveScroll:true});
    }
  }
}
</script>