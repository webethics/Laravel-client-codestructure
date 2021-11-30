<template>
    <div class="location-index-filters">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group form-group-lg">
                            <input class="free-text-search form-control" type="text" name="free_text" placeholder="Search by name/address" v-model="oldFreeText">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group form-group-lg">
                            <select name="county" class="form-control" v-model="county">
                                <option v-for="county in counties" v-bind:class="county.value == county ? 'selected' : ''" v-bind:value="county.value">
                                    {{county.label}}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group form-group-lg">
                            <select name="parent_category" class="form-control" v-model="parentCategory" @change="parentChanged()">
                                <option value="">All Attractions</option>
                                <option v-bind:class="category.value == parentCategory ? 'selected' : ''" v-for="category in categories" v-bind:value="category.value">
                                    {{category.label}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group form-group-lg">
                            <select name="child_category" class="form-control" v-model="childCategory" v-if="childCategories">
                                <option value="">Select sub-category</option>
                                <option v-bind:class="category.value == childCategory ? 'selected' : ''" v-for="category in childCategories" v-bind:value="category.value">
                                    {{category.label}}
                                </option>
                            </select>
                            <select class="form-control" v-else disabled>
                                <option value="">Select sub-category</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-6">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-6">
                        <input type="submit" value="Filter/Search" class="btn btn-primary btn-lg btn-block">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                county: this.selectedCounty,
                parentCategory: this.selectedParentCategory,
                childCategory: this.selectedChildCategory,
                childCategories: false,
            }
        },

        props : [
            'counties',
            'selectedCounty',
            'categories',
            'selectedParentCategory',
            'selectedChildCategory',
            'oldFreeText'
        ],

        created() {
            if (this.parentCategory.length) {
                this.parentChanged(this.childCategory);
            };
        },

        mounted() {
        },


        methods: {
            parentChanged (childCategory = '') {
                let parentKey = _.findKey(this.categories, {'value': this.parentCategory});

                if (parentKey) {
                    this.childCategories = this.categories[parentKey].children;
                } else {
                    this.childCategories = false;
                }

                this.childCategory = childCategory;
            }
        }
    }
</script>