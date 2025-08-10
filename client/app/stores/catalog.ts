interface Product {
    id: number;
    name: string;
    price: number;
    image: string;
}

export const useCatalogStore = defineStore('catalog', () => {
    const products = ref<Product[]>([]);
    const baseUrl = import.meta.server
        ? useRuntimeConfig().catalogBaseUrl
        : useRuntimeConfig().public.catalogBaseUrl;

    const productCount = computed(() => {
        return products.value.length;
    });

    const getProductById = (productId: number) => {
        return products.value.find(product => product.id === productId);
    };

    const fetchProducts = async () => {
        const { data, error } = await $fetch(`/products`, {
            baseURL: baseUrl,
        });

        if (error) {
            throw new Error(`Failed to fetch products: ${error.message}`);
        }

        if (data) {
            products.value = data as Product[];
        }

        return products.value;
    };

    return {
        products,
        productCount,
        getProductById,
        fetchProducts
    };
});

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useCatalogStore, import.meta.hot))
}