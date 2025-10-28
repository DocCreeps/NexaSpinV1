<ul class="space-y-2"
    wire:ignore
>
    @forelse ($candidates as $index => $candidate)

        <li x-data="{
                isEditing: false,
                oldName: '{{ addslashes($candidate) }}',
                newName: '{{ addslashes($candidate) }}',

                startEditing() {
                    this.isEditing = true;
                    this.$nextTick(() => this.$refs.editInput.focus());
                },

                finishEditing() {
                    this.isEditing = false;
                    if (this.oldName !== this.newName) {
                        // 1. Appel Livewire pour mettre à jour la BDD/l'état global
                        this.$wire.call('rename', this.oldName, this.newName);

                        // 2. Mettre à jour oldName pour la prochaine édition
                        this.oldName = this.newName;
                    }
                }
            }"
            wire:key="list-candidate-{{ md5($candidate) }}"
            class="flex items-center gap-2 bg-indigo-50 border border-indigo-200 rounded-lg px-3 py-2">

            {{-- 🟢 FIX : Affiche newName (variable Alpine locale) au lieu de $candidate (variable PHP/Livewire) --}}
            <span x-show="!isEditing" x-text="newName" class="flex-1 text-indigo-900"></span>

            <input type="text"
                   x-show="isEditing"
                   x-model="newName"
                   x-ref="editInput"

                   @blur="finishEditing()"
                   @keydown.enter.prevent="finishEditing()"

                   class="flex-1 px-2 py-1 border border-indigo-200 rounded focus:outline-none focus:ring-2 focus:ring-indigo-400 text-indigo-900"
                   placeholder="Nom du participant"
            />

            <button type="button"
                    x-show="!isEditing"
                    @click="startEditing()"
                    class="px-2 py-1 text-xs bg-indigo-100 text-indigo-600 rounded hover:bg-indigo-200">
                Éditer
            </button>

            <button type="button"
                    @click="$wire.call('remove', oldName)"
                    class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded hover:bg-red-200">
                Supprimer
            </button>
        </li>
    @empty
        <li class="text-gray-400 italic">Aucun participant pour le moment.</li>
    @endforelse
</ul>
