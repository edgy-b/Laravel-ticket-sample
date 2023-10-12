<script setup lang="ts">
import vueFilePond from 'vue-filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginImageSize from 'filepond-plugin-file-validate-size';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import { InertiaForm, router } from '@inertiajs/vue3';

const FilePond = vueFilePond(FilePondPluginImagePreview, FilePondPluginImageSize);

const props = defineProps<{
    form: InertiaForm<{ content: string; images: string[] }>;
}>();

const handleFilePondLoad = (response: string) => {
    props.form.images.push(response);

    return response;
}

const handleFilePondRevert = (uId: string, load: any) => {
    router.delete(`/upload/${uId}`)
    load();
}
</script>

<template>
    <file-pond
        id="images"
        name="images"
        ref="pond"
        className="mt-1 block w-full"
        labelIdle="Drop files here..."
        acceptedFileTypes="image/jpeg, image/png"
        autofocus
        required
        allowMultiple="true"
        maxFileSize="5MB"
        maxFiles="3"
        :server="{
            url: '',
            process: {
                url: '/upload',
                method: 'POST',
                onload: handleFilePondLoad
            },
            revert: handleFilePondRevert,
            headers: {
                'X-CSRF-TOKEN': $page.props.csrf_token
            }
        }"
    />
</template>
