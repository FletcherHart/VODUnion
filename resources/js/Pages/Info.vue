<template>
    <header-layout>
        <div v-if="status == 'about'" class="flex items-center flex-col">
            <h1 class="font-bold">About VODUnion</h1>
            <div class="text-center break-words w-2/3">VODUnion is a video sharing platform built using <a class="text-blue-700 hover:bg-blue-700 hover:text-white rounded" href="https://jetstream.laravel.com/2.x/introduction.html">Laravel Jetstream</a> + <a class="text-blue-700 hover:bg-blue-700 hover:text-white rounded" href="https://inertiajs.com/">Inertia.</a> 
              <br>
              VODUnion currently makes use of <a class="text-blue-700 hover:bg-blue-700 hover:text-white rounded" href="https://www.cloudflare.com/products/cloudflare-stream/">Cloudflare Stream</a> 
              for video hosting & processing, though it is planned to migrate to a personal implementation in the coming months.
            </div>
        </div>
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