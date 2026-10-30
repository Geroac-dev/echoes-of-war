<template>
    <div class="main">
        <div class="dropzone-container" :style="isDragging && 'border-color: green;'" @dragover="dragover" @dragleave="dragleave" @drop="drop">
            <input
                :multiple="multiple"
                type="file"
                name="file"
                :id="inputId"
                class="hidden-input"
                @change="onChange"
                ref="file"
                :accept="accept"
            />
            <label :for="inputId" style="height: 100%;">
                <div v-if="isDragging">Soltar para subir aquí</div>
                <div v-else>Arrastre o <u class="file-label">click</u> para subir</div>
            </label>
            <div class="preview-container mt-4" v-if="files.length">
                <div v-for="(item, index) in files" :key="item.file.name" class="preview-card main">
                    <img v-if="item.isImage" :src="item.preview" class="preview-img"/>
                    <v-icon v-else>mdi-file-document-outline</v-icon>
                    <div class="ml-2 main">
                        <p>{{ item.file.name }} - {{ Math.round(item.file.size / 1000) }}kb</p>
                    </div>
                    <button class="ml-2" type="button" @click="remove(index)">
                        <v-icon>mdi-close</v-icon>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    mounted() {
        window.addEventListener("paste", this.onPaste);
    },
    beforeUnmount() {
        window.removeEventListener("paste", this.onPaste);
    },
    props: {
        multiple: { type: Boolean,  default: true },
        accept: {   type: String,   default: "image/*" },
        inputId: {  type: String,   default: "inputId" }
    },
    data() {
        return {
            isDragging: false,
            files: [],
        };
    },
    methods: {
        onChange() {
            this.addFiles(this.$refs.file.files);
        },
        addFiles(list) {
            let incoming = Array.from(list).map(file => ({
                file,
                isImage: file.type.startsWith("image/"),
                preview: file.type.startsWith("image/") ? URL.createObjectURL(file) : null,
            }));
            if (!this.multiple) {
                this.files.forEach(f => f.preview && URL.revokeObjectURL(f.preview));
                this.files = [incoming[0]];
            } else {
                this.files.push(...incoming);
            }
            this.$emit("changed", this.files.map(f => f.file));
        },
        dragover(e) {
            e.preventDefault();
            this.isDragging = true;
        },
        dragleave() {
            this.isDragging = false;
        },
        drop(e) {
            e.preventDefault();
            this.addFiles(e.dataTransfer.files);
            this.isDragging = false;
        },
        remove(index) {
            const f = this.files[index];
            if (f.isImage && f.preview) URL.revokeObjectURL(f.preview);
            this.files.splice(index, 1);
            this.$emit("changed", this.files.map(f => f.file));
        },
        onPaste(e) {
            if (!e.clipboardData || !e.clipboardData.items) return;
            const items = e.clipboardData.items;
            for (let i = 0; i < items.length; i++) {
                const item = items[i];
                if (item.type.indexOf("image") !== -1) {
                    const blob = item.getAsFile();
                    if (blob) {
                        const file = new File(
                            [blob],
                            "captura-" + Date.now() + ".png",
                            { type: "image/png" }
                        );
                        this.addFiles([file]);
                    }
                }
            }
        },
    },
};
</script>

<style scoped>
.file-label {
    font-size: 20px;
    display: block;
    cursor: pointer;
}
.main {
    align-items: center;
    justify-content: center;
    text-align: center;
}
.dropzone-container {
    padding: 4rem;
    border: 2px dashed #aaa;
}
.hidden-input {
    opacity: 0;
    position: absolute;
    width: 1px;
    height: 1px;
}
.preview-container {
    flex-wrap: wrap;
    display: flex;
    gap: 10px;
    margin-top: 2rem;
}
.preview-card {
    display: flex;
    align-items: center;
    border: 1px solid #a2a2a2;
    padding: 5px;
}
.preview-img {
    width: 50px;
    height: 50px;
    border-radius: 5px;
    margin-right: 10px;
}
</style>
