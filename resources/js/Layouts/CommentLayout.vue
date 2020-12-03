<template>
      <div class="py-12">
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