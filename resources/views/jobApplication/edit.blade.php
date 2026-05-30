<x-app-layout>
    <x-slot name="header">Edit Application</x-slot>

    <div class="max-w-4xl mx-auto">

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl text-sm font-medium text-red-700 dark:text-red-300 flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                Please fix the errors below.
            </div>
        @endif

        <header class="flex-shrink-0 flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('job-applications.show', $jobApplication) }}" class="w-9 h-9 border border-slate-200 dark:border-slate-600 rounded-lg flex items-center justify-center text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Edit Application</h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-1">Update the details for {{ $jobApplication->company?->name ?? 'this application' }}.</p>
                </div>
            </div>
        </header>

        <form method="POST" action="{{ route('job-applications.update', $jobApplication) }}" enctype="multipart/form-data" class="space-y-6" x-data="{
            fields: {
                job_title: '{{ old('job_title', $jobApplication->job_title) }}',
                status: '{{ old('status', $jobApplication->status->value) }}',
            },
            errors: {},
            validate(field) {
                if (field === 'job_title' && !this.fields.job_title.trim()) {
                    this.errors.job_title = 'Job title is required.';
                } else if (field === 'job_title') {
                    delete this.errors.job_title;
                }
                if (field === 'status' && !this.fields.status) {
                    this.errors.status = 'Please select a status.';
                } else if (field === 'status') {
                    delete this.errors.status;
                }
            },
            valid(field) { return !this.errors[field] && this.fields[field] && this.fields[field].toString().trim(); }
        }">
            @csrf
            @method('PUT')

            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 space-y-4">
                <h2 class="text-base font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-3 flex items-center gap-2">
                    <i class="fas fa-briefcase text-[#2563eb]"></i> Role Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="job_title" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Job Title <span class="text-red-500">*</span></label>
                        <input type="text" id="job_title" name="job_title" x-model="fields.job_title" @blur="validate('job_title')" required placeholder="e.g. Backend Developer"
                            class="w-full px-3.5 py-2 rounded-lg border text-sm transition outline-none"
                            :class="errors.job_title ? 'border-red-400 dark:border-red-500 bg-red-50 dark:bg-red-900/10' : (valid('job_title') ? 'border-emerald-400 dark:border-emerald-500 bg-emerald-50/50 dark:bg-emerald-900/10' : 'border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb]')">
                        <template x-if="errors.job_title">
                            <p class="text-xs text-red-500 mt-1" x-text="errors.job_title"></p>
                        </template>
                        @error('job_title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Company Name <span class="text-red-500">*</span></label>
                        <x-company-combobox
                            :companies="$companies"
                            :selected-id="old('company_id', $jobApplication->company_id)"
                            :selected-name="old('company_id', $jobApplication->company_id) ? ($companies->firstWhere('id', old('company_id', $jobApplication->company_id))?->name ?? $jobApplication->company?->name) : ''"
                            :error="$errors->first('company_id') ?? $errors->first('new_company_name')"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Pipeline Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" x-model="fields.status" @change="validate('status')"
                            class="w-full px-3.5 py-2 rounded-lg border bg-white dark:bg-slate-900 dark:text-slate-300 text-sm transition cursor-pointer outline-none"
                            :class="errors.status ? 'border-red-400 dark:border-red-500' : 'border-slate-200 dark:border-slate-600 focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb]'">
                            @foreach (\App\Enums\JobApplicationStatus::cases() as $s)
                                <option value="{{ $s->value }}" {{ old('status', $jobApplication->status->value) === $s->value ? 'selected' : '' }}>{{ $s->label() }}</option>
                            @endforeach
                        </select>
                        <template x-if="errors.status">
                            <p class="text-xs text-red-500 mt-1" x-text="errors.status"></p>
                        </template>
                        @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="location_type" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Job Location Type</label>
                        <select id="location_type" name="location_type" class="w-full px-3.5 py-2 rounded-lg border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 bg-white focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb] outline-none text-sm transition cursor-pointer">
                            <option value="">Select...</option>
                            @foreach (\App\Enums\JobLocationType::cases() as $lt)
                                <option value="{{ $lt->value }}" {{ old('location_type', $jobApplication->location_type?->value) === $lt->value ? 'selected' : '' }}>{{ $lt->label() }}</option>
                            @endforeach
                        </select>
                        @error('location_type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="location_city" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Location</label>
                        <input type="text" id="location_city" name="location_city" value="{{ old('location_city', $jobApplication->location_city) }}" placeholder="e.g. San Francisco, CA" class="w-full px-3.5 py-2 rounded-lg border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb] outline-none text-sm transition">
                        @error('location_city') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div x-data="{
                    links: {{ json_encode(
                        collect($jobApplication->links ?? [])->map(fn($url, $label) => ['label' => $label, 'url' => $url])->values()->toArray()
                    ) }},
                    addLink() { this.links.push({ label: '', url: '' }) },
                    removeLink(index) { this.links.splice(index, 1) }
                }">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Links</label>
                        <button type="button" @click="addLink()" class="text-xs font-medium text-[#2563eb] dark:text-blue-400 hover:text-blue-700 flex items-center gap-1">
                            <i class="fas fa-plus text-[10px]"></i> Add Link
                        </button>
                    </div>
                    <template x-for="(link, index) in links" :key="index">
                        <div class="flex items-center gap-2 mb-2">
                            <input type="text" x-model="link.label" :name="'links[' + index + '][label]'" placeholder="Label (e.g. LinkedIn)" class="flex-1 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb] outline-none text-sm transition placeholder:text-slate-400" required>
                            <input type="url" x-model="link.url" :name="'links[' + index + '][url]'" placeholder="https://..." class="flex-[2] px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb] outline-none text-sm transition placeholder:text-slate-400" required>
                            <button type="button" @click="removeLink(index)" class="text-red-400 hover:text-red-600 text-sm p-1.5 flex-shrink-0">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </template>
                    <p x-show="links.length === 0" class="text-xs text-slate-400 dark:text-slate-500 italic">No links added yet.</p>
                </div>
                <div>
                    <label for="applied_at" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Date Applied</label>
                    <input type="date" id="applied_at" name="applied_at" value="{{ old('applied_at', $jobApplication->applied_at?->format('Y-m-d')) }}" class="w-full px-3.5 py-2 rounded-lg border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb] outline-none text-sm transition">
                    @error('applied_at') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="salary_min" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Salary Min</label>
                        <input type="number" id="salary_min" name="salary_min" step="0.01" min="0" value="{{ old('salary_min', $jobApplication->salary_min) }}" placeholder="e.g. 50000" class="w-full px-3.5 py-2 rounded-lg border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb] outline-none text-sm transition">
                        @error('salary_min') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="salary_max" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Salary Max</label>
                        <input type="number" id="salary_max" name="salary_max" step="0.01" min="0" value="{{ old('salary_max', $jobApplication->salary_max) }}" placeholder="e.g. 120000" class="w-full px-3.5 py-2 rounded-lg border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb] outline-none text-sm transition">
                        @error('salary_max') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="currency" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Currency</label>
                        <select id="currency" name="currency" class="w-full px-3.5 py-2 rounded-lg border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 bg-white focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb] outline-none text-sm transition cursor-pointer">
                            <option value="">Select...</option>
                            <option value="USD" {{ old('currency', $jobApplication->currency) === 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="EUR" {{ old('currency', $jobApplication->currency) === 'EUR' ? 'selected' : '' }}>EUR</option>
                            <option value="GBP" {{ old('currency', $jobApplication->currency) === 'GBP' ? 'selected' : '' }}>GBP</option>
                            <option value="MAD" {{ old('currency', $jobApplication->currency) === 'MAD' ? 'selected' : '' }}>MAD</option>
                        </select>
                        @error('currency') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 space-y-4">
                <h2 class="text-base font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-3 flex items-center gap-2">
                    <i class="far fa-file-alt text-[#2563eb]"></i> Details & Assets
                </h2>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tags</label>
                    @php $selectedTagIds = old('tags', $jobApplication->tags->pluck('id')->toArray()); @endphp
                    <div x-data="{ selectedTags: {{ json_encode($selectedTagIds) }} }">
                        <template x-for="(tagId, index) in selectedTags" :key="index">
                            <input type="hidden" name="tags[]" :value="tagId">
                        </template>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="tag in {{ json_encode($tags->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'color' => $t->color])->values()) }}" :key="tag.id">
                                <button type="button" @click="selectedTags.includes(tag.id) ? selectedTags = selectedTags.filter(t => t !== tag.id) : selectedTags.push(tag.id)"
                                    :class="selectedTags.includes(tag.id) ? 'ring-2 ring-offset-1 ring-[#2563eb] dark:ring-offset-slate-800' : 'opacity-70 hover:opacity-100'"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium transition-all duration-150 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                                    <span :style="'background-color: ' + tag.color" class="w-2 h-2 rounded-full"></span>
                                    <span x-text="tag.name"></span>
                                </button>
                            </template>
                        </div>
                        @if($tags->isEmpty())
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">No tags created yet. <a href="{{ route('tags.index') }}" class="text-[#2563eb] dark:text-blue-400 hover:underline">Manage tags</a></p>
                        @endif
                        @error('tags') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Notes / Description</label>
                    <textarea id="notes" name="notes" rows="4" placeholder="Paste job description requirements, interview panel details, or referral info here..." class="w-full px-3.5 py-2 rounded-lg border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb] outline-none text-sm transition resize-none">{{ old('notes', $jobApplication->notes) }}</textarea>
                    @error('notes') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Attach Relevant Files (Resume, Cover Letter)</label>
                    <div class="border-2 border-dashed border-slate-200 dark:border-slate-600 rounded-xl p-6 text-center hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition cursor-pointer group">
                        <input type="file" class="hidden" id="file-upload" multiple>
                        <label for="file-upload" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-3xl text-slate-300 dark:text-slate-500 group-hover:text-[#2563eb] transition mb-2"></i>
                            <div class="font-bold text-slate-700 dark:text-slate-300 text-sm">Click to upload or drag files here</div>
                            <div class="text-xs text-slate-400 dark:text-slate-500 font-medium mt-0.5">PDF, DOCX up to 10MB</div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('job-applications.show', $jobApplication) }}" class="px-5 py-2.5 border border-slate-200 dark:border-slate-600 rounded-lg font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-sm">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 bg-[#2563eb] text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-sm">
                    Update Application
                </button>
            </div>

        </form>
    </div>
</x-app-layout>