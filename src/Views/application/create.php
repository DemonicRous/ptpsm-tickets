<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 md:p-8">
        <div class="flex items-center gap-3 mb-6">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h1 class="text-2xl font-bold">Новая заявка</h1>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/application/store" enctype="multipart/form-data" class="space-y-5">
            <?= \Core\CSRF::input() ?>
            
            <div>
                <label class="block text-sm font-medium mb-1">Кабинет / Место <span class="text-red-500">*</span></label>
                <input type="text" name="name_org" value="<?= htmlspecialchars($old['name_org'] ?? '') ?>" required
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700">
            </div>
            
            <!-- Категория с древовидным select -->
            <div x-data="categoryTreeSelect" class="relative">
                <label class="block text-sm font-medium mb-1">Категория</label>
                
                <!-- Кнопка выбора -->
                <div @click="open = !open" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 cursor-pointer flex justify-between items-center transition-colors">
                    <span x-text="selectedName || '-- Выберите категорию --'" class="text-gray-700 dark:text-gray-300"></span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                
                <!-- Выпадающее дерево -->
                <div x-show="open" x-cloak x-transition.duration.200ms class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border rounded-lg shadow-lg overflow-hidden">
                    <!-- Поиск -->
                    <div class="p-2 sticky top-0 bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" x-model="search" placeholder="Поиск категории..." 
                                   class="w-full pl-8 pr-3 py-1 border rounded dark:bg-gray-700 dark:border-gray-600 text-sm focus:ring-1 focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <!-- Дерево категорий -->
                    <div class="max-h-60 overflow-y-auto">
                        <template x-for="cat in filteredTree" :key="cat.category_id">
                            <div>
                                <div @click="toggleCategory(cat)" 
                                     class="flex items-center justify-between px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                                     :style="{ paddingLeft: (cat.level * 20 + 12) + 'px' }">
                                    <div class="flex items-center gap-1">
                                        <svg x-show="cat.children && cat.children.length" 
                                             class="w-4 h-4 transition-transform duration-150" 
                                             :class="{ 'rotate-90': expandedCategories.includes(cat.category_id) }"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                        <span x-show="!cat.children || !cat.children.length" class="w-4"></span>
                                        <span x-text="cat.name" class="text-sm"></span>
                                    </div>
                                    <span x-show="selectedId == cat.category_id" class="text-blue-600 dark:text-blue-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                </div>
                                <div x-show="expandedCategories.includes(cat.category_id)" x-cloak>
                                    <template x-for="child in cat.children" :key="child.category_id">
                                        <div @click="selectCategory(child.category_id, child.name)"
                                             class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer flex items-center justify-between"
                                             :style="{ paddingLeft: ((child.level) * 20 + 12) + 'px' }">
                                            <span x-text="child.name" class="text-sm"></span>
                                            <span x-show="selectedId == child.category_id" class="text-blue-600 dark:text-blue-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <div x-show="filteredTree.length === 0" class="px-3 py-4 text-gray-500 text-center text-sm">
                            Ничего не найдено
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="category_id" :value="selectedId">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium mb-1">Приоритет</label>
                    <select name="priority_id" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700">
                        <?php foreach ($priorities as $pri): ?>
                            <option value="<?= $pri['priority_id'] ?>" <?= (($old['priority_id'] ?? 2) == $pri['priority_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($pri['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Желаемая дата выполнения</label>
                    <input type="date" name="expected_date" value="<?= htmlspecialchars($old['expected_date'] ?? '') ?>"
                           class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700">
                    <p class="text-xs text-gray-500 mt-1">Формат: ГГГГ-ММ-ДД (например, 2025-12-31) или ДД.ММ.ГГГГ</p>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-1">Описание проблемы <span class="text-red-500">*</span></label>
                <textarea name="message" rows="5" required
                          class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700"><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-1">Вложения (несколько файлов)</label>
                <input type="file" name="attachments[]" multiple
                       class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300">
            </div>
            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Отправить заявку
            </button>
        </form>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

<script src="//unpkg.com/alpinejs" defer></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('categoryTreeSelect', () => ({
            open: false,
            search: '',
            selectedId: '<?= htmlspecialchars($old['category_id'] ?? '') ?>',
            selectedName: '',
            categories: <?= json_encode($categories) ?>,
            expandedCategories: [],
            
            get tree() {
                const map = {};
                const roots = [];
                this.categories.forEach(cat => {
                    map[cat.category_id] = { ...cat, children: [] };
                });
                this.categories.forEach(cat => {
                    if (cat.parent_id && map[cat.parent_id]) {
                        map[cat.parent_id].children.push(map[cat.category_id]);
                    } else if (!cat.parent_id) {
                        roots.push(map[cat.category_id]);
                    }
                });
                return roots;
            },
            
            get filteredTree() {
                if (!this.search.trim()) return this.tree;
                const lowerSearch = this.search.toLowerCase();
                const filterNode = (node) => {
                    const matches = node.name.toLowerCase().includes(lowerSearch);
                    const filteredChildren = (node.children || []).map(child => filterNode(child)).filter(Boolean);
                    if (matches || filteredChildren.length) {
                        return { ...node, children: filteredChildren };
                    }
                    return null;
                };
                return this.tree.map(node => filterNode(node)).filter(Boolean);
            },
            
            toggleCategory(cat) {
                if (!cat.children || cat.children.length === 0) {
                    this.selectCategory(cat.category_id, cat.name);
                } else {
                    const index = this.expandedCategories.indexOf(cat.category_id);
                    if (index > -1) {
                        this.expandedCategories.splice(index, 1);
                    } else {
                        this.expandedCategories.push(cat.category_id);
                    }
                }
            },
            
            selectCategory(id, name) {
                this.selectedId = id;
                this.selectedName = name;
                this.open = false;
            },
            
            init() {
                const findAndExpand = (items, targetId, path = []) => {
                    for (let item of items) {
                        if (item.category_id == targetId) {
                            path.forEach(id => this.expandedCategories.push(id));
                            return true;
                        }
                        if (item.children && findAndExpand(item.children, targetId, [...path, item.category_id])) {
                            return true;
                        }
                    }
                    return false;
                };
                if (this.selectedId) {
                    findAndExpand(this.tree, this.selectedId);
                    const selected = this.categories.find(c => c.category_id == this.selectedId);
                    if (selected) this.selectedName = selected.name;
                }
            }
        }));
    });
</script>