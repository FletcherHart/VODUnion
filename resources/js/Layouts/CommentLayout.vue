<template>
  <section class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
              <div v-if="$page.props.auth.user.loggedIn">
              <form @submit.prevent="submit">
                <textarea class="border-solid border-2 border-black-600 w-full" id="text" rows="4" v-model="form.text" placeholder="Comment" aria-label="make comment"/>
                <button type="submit" class="bg-blue-600 text-white rounded pl-4 pr-4" aria-label="submit comment">Submit</button>
                <div>
                <jet-input-error v-if="form.errors.text" :message="form.errors.text"/>
                </div>
              </form>
            </div>
            <div v-else> 
              Please login to post a comment.
            </div>
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
              <h1 class="text-xl font-bold">Total Comments: {{totalComments}}</h1>
              <hr>
              <article v-for="comment in $page.props.comments" :key="comment.id">
                <div class="my-3 ml-2 mr-2">
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
                  <p class="break-words whitespace-pre-line whitespace-pre-wrap">{{comment.text}}</p>
                </div>
                <hr>
              </article>
          </div>
      </div>
  </section>
</template>

<script>
import JetInputError from '../Jetstream/InputError'
export default {
  components: {
    JetInputError,
  },
  props: {
    id: Number,
    ownerID: Number,
    totalComments: Number,
    errors: Object
  },
  data() {
    return {
      form: this.$inertia.form({
        '_method': 'POST',
        text:null
      }, {
              bag: 'comment',
              resetOnSuccess: false,
          }),
        }
  },
  updated: function() {
      if(this.form.recentlySuccessful) {
          this.form.reset();
          this.form.recentlySuccessful = false;
      }
  },
  methods: {
    submit() {
      this.form.post('/video/' + this.id + '/comment', {
          preserveScroll: true
      });
      //this.form.text = null;
    },
    deleteComment(id) {
      this.$inertia.delete('/comment/'+id, {preserveScroll:true});
    }
  }
}
</script>