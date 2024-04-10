<template>
    <!-- eslint-disable -->
    <a17-inputframe :error="error" :note="note" :label="label" :name="name" :required="required">
        <label class="mb-2 block text-gray-700" for="query-artwork">
            Query Artwork by Object or Reference Number
        </label>
        <div :class="['form__field', textfieldClasses]">
            <input
                type="search"
                :placeholder="placeholder"
                :name="name"
                :id="name"
                :disabled="disabled"
                :required="required"
                :readonly="readonly"
                :autofocus="autofocus"
                :autocomplete="autocomplete"
                v-model="queryValue"
                @focus="onFocus"
                @blur="onBlur"
                @input="onInput"
            />
        </div>
        <transition name="fade_search-overlay">
            <div
                class="relative mt-2 max-h-96 overflow-y-scroll rounded-md border border-gray-300 bg-white shadow-sm"
                v-show="readyToShowResult"
            >
                <ul role="list" class="divide-y divide-gray-100">
                    <li
                        v-for="artwork in queryResults"
                        :key="artwork.id"
                        class="flex cursor-pointer gap-x-4 px-5 py-5 hover:bg-slate-200"
                        @click="selectArtwork(artwork)"
                    >
                        <div class="h-12 w-12 flex-none">
                            <img v-if="artwork.thumbnail" :src="artwork.thumbnail" class="h-full w-full bg-gray-50" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ artwork.title }}</p>
                            <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ artwork.artist_display }}</p>
                        </div>
                    </li>
                    <li class="bg-slate-100 px-5 py-5" v-show="loading">Loading...</li>
                    <li class="bg-slate-100 px-5 py-5" v-show="readyToShowResult && !queryResults.length && !loading">
                        No results found
                    </li>
                </ul>
            </div>
        </transition>
    </a17-inputframe>
</template>

<script>
/* eslint-disable */
import axios from 'axios';
import debounce from 'lodash/debounce';
import InputMixin from '@/mixins/input';
import InputframeMixin from '@/mixins/inputFrame';
import LocaleMixin from '@/mixins/locale';
import { FORM } from '@/store/mutations';

const CancelToken = axios.CancelToken;
let source = CancelToken.source();

export default {
    name: 'A17QueryArtwork',
    mixins: [InputMixin, InputframeMixin, LocaleMixin],
    props: {
        updateFormFields: {
            type: Array,
            default: () => [],
        },
    },
    data: function () {
        return {
            focused: false,
            queryValue: '',
            loading: false,
            readyToShowResult: false,
            queryResults: [],
        };
    },
    computed: {
        textfieldClasses: function () {
            return {
                's--focus': this.focused,
                's--disabled': this.disabled,
            };
        },
    },
    methods: {
        onFocus: function (event) {
            this.focused = true;
            this.$emit('focus');
        },
        onBlur: function (event) {
            this.focused = false;
            this.$emit('blur');
        },
        onInput: debounce(function (event) {
            this.queryValue = event.target.value;
            if (this.queryValue && this.queryValue.length > 0) {
                this.fetchQueryResults();
            }
        }, 600),
        fetchQueryResults: function () {
            const self = this;

            if (this.loading) {
                source.cancel();
                source = CancelToken.source();
            } else {
                this.loading = true;
            }

            this.readyToShowResult = true;

            this.$http
                .get('admin/artworks/query', {
                    params: { search: this.queryValue },
                    cancelToken: source.token,
                })
                .then(
                    function (response) {
                        self.queryResults = response.data;
                        self.loading = false;
                    },
                    function (response) {
                        if (!axios.isCancel(response)) {
                            self.loading = false;
                            self.queryResults = [];
                        }
                    },
                );
        },
        selectArtwork: function (artwork) {
            this.queryValue = '';
            this.queryResults = [];
            this.readyToShowResult = false;

            for (const field of this.updateFormFields) {
                let payload = {};
                payload.name = field.formField;
                payload.value = artwork[field.artworkField];
                if (field.locale) payload.locale = field.locale;

                this.$store.commit(FORM.UPDATE_FORM_FIELD, payload);
            }
        },
    },
};
</script>
