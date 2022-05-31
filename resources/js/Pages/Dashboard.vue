<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import Pagination from '@/Components/Pagination'
import { Head } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

defineProps({
    articles: Object,
});

 const destroy = (id) => {
    if (confirm('Are u sure you want to delete this article?')) {
        Inertia.delete(route('articles.destroy', id))
    }
 }

</script>

<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Hacker news articles
            </h2>
        </template>

        <template #default>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Title
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Link
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Points
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date Created
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <span class="sr-only">Delete</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(article, index) in articles.data" :key="article.id" class="bg-white border-b">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900">
                            {{ article.title }}
                        </th>
                        <td class="px-6 py-4">
                            <a :href="article.link">{{ article.link }}</a>
                        </td>
                        <td class="px-6 py-4">
                            {{ article.points}}
                        </td>
                        <td class="px-6 py-4">
                            {{ article.date_created }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button @click="destroy(article.id)" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>


            <pagination :links="articles.links" />
        </template>
    </BreezeAuthenticatedLayout>
</template>
