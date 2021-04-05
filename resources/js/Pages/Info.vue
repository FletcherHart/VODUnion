<template>
    <header-layout>
        <article v-if="status == 'about'" class="flex items-center flex-col">
            <h1 class="font-bold">About VODUnion</h1>
            <div class="text-center break-words w-2/3 article-body">VODUnion is a video sharing platform built using <a href="https://jetstream.laravel.com/2.x/introduction.html">Laravel Jetstream</a> + <a href="https://inertiajs.com/">Inertia.</a> 
              <br>
              VODUnion currently makes use of <a href="https://www.cloudflare.com/products/cloudflare-stream/">Cloudflare Stream</a> 
              for video hosting & processing, though it is planned to migrate to a personal implementation in the coming months.
            </div>
        </article>
    </header-layout>
</template>

<script>
import HeaderLayout from '@/Layouts/HeaderLayout'
export default {
  components: {
      HeaderLayout
  },
  props: {
    status: String,
  },
}
</script>