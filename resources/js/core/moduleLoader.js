// resources/js/core/moduleLoader.js

/**
 * Module Loader - Handles dynamic module loading and cleanup
 * Prevents duplicate event listeners and ensures proper re-initialization
 */
class ModuleLoader {
    constructor() {
        this.loadedModules = new Map();
        this.cleanupFunctions = new Map();
    }

    /**
     * Load a module by route
     * @param {string} route - The route path
     * @param {Function} moduleLoader - Function that loads the module
     */
    async loadModule(route, moduleLoader) {
        // Cleanup previous module if it exists
        this.cleanup(route);

        // Check if module is already loaded
        if (this.loadedModules.has(route)) {
            // Re-initialize the module
            const module = this.loadedModules.get(route);
            if (typeof module.reinit === 'function') {
                module.reinit();
                return;
            }
        }

        try {
            // Load the module
            const cleanup = await moduleLoader();
            
            // Store module and cleanup function
            this.loadedModules.set(route, {
                cleanup: cleanup || (() => {}),
                loadedAt: Date.now()
            });

            if (cleanup && typeof cleanup === 'function') {
                this.cleanupFunctions.set(route, cleanup);
            }
        } catch (error) {
            console.error(`Failed to load module for route: ${route}`, error);
        }
    }

    /**
     * Cleanup a specific module
     * @param {string} route - The route to cleanup
     */
    cleanup(route) {
        if (this.cleanupFunctions.has(route)) {
            const cleanup = this.cleanupFunctions.get(route);
            if (typeof cleanup === 'function') {
                cleanup();
            }
            this.cleanupFunctions.delete(route);
        }
        this.loadedModules.delete(route);
    }

    /**
     * Cleanup all modules
     */
    cleanupAll() {
        this.cleanupFunctions.forEach((cleanup) => {
            if (typeof cleanup === 'function') {
                cleanup();
            }
        });
        this.cleanupFunctions.clear();
        this.loadedModules.clear();
    }
}

// Export singleton instance
export default new ModuleLoader();