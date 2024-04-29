<template>
    <!-- eslint-disable -->
    <a17-inputframe :error="error" :note="note" :label="label" :name="name" :required="required">
        <label class="block mb-2 text-gray-700" for="query-artwork">
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
                class="relative mt-2 bg-white border border-gray-300 rounded-md shadow-sm max-h-96"
                :class="{'overflow-y-scroll': queryResults.length > 4}"
                v-show="readyToShowResult"
            >
                <ul role="list" class="divide-y divide-gray-100">
                    <li
                        v-for="artwork in queryResults"
                        :key="artwork.id"
                        class="flex px-5 py-5 cursor-pointer gap-x-4 hover:bg-slate-200"
                        @click="selectArtwork(artwork)"
                    >
                        <div class="flex-none w-12 h-12">
                            <img v-if="artwork.thumbnail" :src="artwork.thumbnail" class="w-full h-full bg-gray-50" alt="{{ artwork.title }}" />
                        </div>
                        <div class="w-full">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ artwork.title }}</p>
                            <div class="flex justify-between">
                                <p class="mt-1 text-xs leading-5 text-gray-500 truncate">{{ artwork.artist }}</p>
                                <span v-if="artwork.is_on_view" class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-md">✅ On View</span>
                                <span v-else class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-md">❌ On View</span>
                            </div>
                        </div>
                    </li>
                    <li class="px-5 py-5 bg-slate-100" v-show="loading">Loading...</li>
                    <li class="px-5 py-5 bg-slate-100" v-show="readyToShowResult && !queryResults.length && !loading">
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
