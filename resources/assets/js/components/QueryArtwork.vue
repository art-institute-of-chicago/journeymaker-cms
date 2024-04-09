<template>
    <!-- eslint-disable -->
    <a17-inputframe
        :error="error"
        :note="note"
        :label="label"
        :name="name"
        :required="required"
    >
        <label
            class="checkbox__label"
            :for="name"
        >
            Query API
        </label>
        <div
            class="form__field"
            :class="textfieldClasses"
        >
            <input
                type="search"
                placeholder="Query Artwork"
                name="artwork"
                id="query-artwork"
                autofocus="true"
                @focus="onFocus"
                @blur="onBlur"
                @input="onInput"
            />
        </div>
        <transition name="fade_search-overlay">
            <div
                class="relative mt-2 max-h-96 overflow-y-scroll shadow-sm bg-white rounded-md border border-gray-300"
                v-show="readyToShowResult"
            >
                <ul role="list" class="divide-y divide-gray-100">
                    <li
                        v-for="item in queryResults"
                        :key="item.id"
                        class="flex gap-x-4 py-5 px-5 hover:bg-slate-200 cursor-pointer"
                    >
                        <div class="h-12 w-12 flex-none">
                            <img v-if="item.thumbnail" :src="item.thumbnail" class="w-full h-full bg-gray-50" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ item.title }}</p>
                            <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ item.artist_display }}</p>
                        </div>
                    </li>

                    <li class="py-5 px-5 bg-slate-100" v-show="loading">
                        Loading...
                    </li>
                    <li class="py-5 px-5 bg-slate-100" v-show="readyToShowResult && !queryResults.length && !loading">
                        No results found
                    </li>
                </ul>
            </div>
        </transition>
    </a17-inputframe>
</template>

<script>
/* eslint-disable */
import axios from 'axios'
import debounce from 'lodash/debounce'
import InputMixin from '@/mixins/input'
import InputframeMixin from '@/mixins/inputFrame'
import a17Search from '@/components/Search.vue'

const CancelToken = axios.CancelToken
let source = CancelToken.source()

export default {
  name: 'A17QueryArtwork',
    components: {
        a17Search
    },
  mixins: [InputMixin, InputframeMixin],
  props: {

  },
  data: function () {
    return {
        focused: false,
        queryValue: '',
        loading: false,
        readyToShowResult: false,
        queryResults: []
    }
  },
  computed: {
      textfieldClasses: function () {
        return {
          's--focus': this.focused,
          's--disabled': this.disabled
        }
      }
    },
  methods: {
    onFocus: function (event) {
        this.focused = true

        this.$emit('focus')
      },
      onBlur: function (event) {
        this.focused = false

        this.$emit('blur')
      },
      onInput: debounce(function (event) {
        this.queryValue = event.target.value
        if (this.queryValue && this.queryValue.length > 0) {
            this.fetchQueryResults()
        }
      }, 600),
      fetchQueryResults: function () {
        const self = this
        const data = {
          search: this.queryValue
        }

        if (this.loading) {
          source.cancel()
          source = CancelToken.source()
        } else {
          this.loading = true
        }

        this.readyToShowResult = true

        this.$http.get('admin/artworks/query', {
          params: data,
          cancelToken: source.token
        }).then(function (resp) {
          self.queryResults = resp.data
          self.loading = false
          console.log('fetchQueryResults', resp.data);
        }, function (resp) {
          // handle error
          if (!axios.isCancel(resp)) {
            self.loading = false
          }
        })
      },
  },
  mounted: function () {

  },
}
</script>
