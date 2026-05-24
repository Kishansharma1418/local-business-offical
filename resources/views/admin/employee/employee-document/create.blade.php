@extends('include.master')
@push('styles')
    <style>
        .btn-outline-primary.btn-sm:hover {
            background-color: #0d6efd;
            color: #fff !important;
            border-color: #0d6efd;
        }

        .single-doc-section .form-control,
        .single-doc-section .btn {
            height: 42px;
        }

        .single-doc-section {
            margin-bottom: 20px;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: nowrap;
        }

        .file-upload {
            flex: 1;
            min-width: 250px;
        }

        .btn-sm {
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
        }

        .uploaded-file-name {
            font-size: 13px;
            color: #555;
            font-style: italic;
            margin-top: 5px;
            display: block;
            text-align: left;
            line-height: 18px;
        }

        .single-doc-section hr {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 6px;
        }

        .drop-zone {
            position: relative;
            border: 2px dashed #d0d7e2;
            border-radius: 10px;
            padding: 18px;
            text-align: center;
            cursor: pointer;
            background: #f9fbff;
            transition: all 0.25s ease;
        }

        .drop-zone:hover {
            border-color: #0d6efd;
            background: #eef4ff;
        }

        .drop-zone input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        .drop-zone i {
            font-size: 26px;
            color: #0d6efd;
            margin-bottom: 6px;
        }

        .drop-zone p {
            margin: 0;
            font-size: 13px;
            color: #555;
        }

        .drop-zone span {
            font-size: 12px;
            color: #888;
        }

        .upload-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 10px;
        }

        .doc-card {
            background: #ffffff;
            border: 1px solid #edf0f7;
            border-radius: 12px;
            padding: 16px;
        }

        .doc-title {
            font-weight: 600;
            margin-bottom: 12px;
            color: #1f2d3d;
        }

        .save-single-doc {
            color: #fff !important;
        }

        .save-single-doc:hover,
        .save-single-doc:focus,
        .save-single-doc:active {
            color: #fff !important;
        }

        .file-preview {
            margin-top: 10px;
            cursor: pointer;
        }

        .file-preview img {
            width: 100%;
            max-height: 140px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e2e6ef;
        }

        .file-preview .pdf-preview {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #0d6efd;
            font-weight: 500;
        }

        .file-preview .pdf-preview i {
            font-size: 22px;
        }

        .drop-zone.has-preview input[type="file"] {
            pointer-events: none;
        }
        
    </style>
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Employee Documents</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('employee.index') }}" class="text-decoration-none text-body fs-14 hover">
                            Employee List
                        </a>
                    </li>
                    <li class="breadcrumb-item active"><span class="text-secondary">Employee Documents</span></li>
                </ol>
            </nav>
        </div>
        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
            <div class="card-body p-4">
                <div class="row g-3">

                    @php
                        $documents = [
                            'aadhaar' => 'Aadhaar Card',
                            'pan' => 'PAN Card',
                            'voter_id' => 'Voter ID',
                            'driving_license' => 'Driving License',
                            'resume' => 'Resume',
                            'experience_letter' => 'Experience Letter',
                            'relieving_letter' => 'Relieving Letter',
                            'degree_certificates' => 'Degree Certificates',
                            'photo' => 'Photo',
                            'salary_slips' => 'Salary Slip',
                            'other' => 'Other Documents',
                             'academic' => 'Academic Documents',
                        ];
                    @endphp
                    @foreach ($documents as $type => $label)
                        @php
                            $doc = \App\Models\EmployeeDocument::where('employee_id', $employee_id)
                                ->where('document_type', $type)
                                ->get();
                        @endphp

                        <div class="col-12">
                            <h5 class="fw-semibold mb-2">{{ $label }}</h5>
                            <div class="row g-3 single-doc-section">
                                <input type="hidden" name="document_type" value="{{ $type }}">

                                {{-- ✅ OTHER DOCUMENTS --}}
                                @if ($type === 'other' || $type === 'academic' || $type === 'degree_certificates')
                                    <div class="row g-3">
                                        @for ($i = 1; $i <= 10; $i++)
                                            @php
                                                $row = $doc[$i - 1] ?? null;
                                            @endphp 

                                            <div class="col-lg-6 col-md-6">
                                                <div class="doc-card h-100">
                                                    <div class="doc-title" >
                                                        {{ $label }} {{ $i }}
                                                    </div>  
                                                    
                                                    <input type="text" name="document_name[]" class="form-control mb-2" 
                                                        placeholder="Enter document name"
                                                        value="{{ $row?->document_name }}">

                                                    <div class="drop-zone">
                                                        <i class="ri-file-upload-line"></i>
                                                        <p>Drop file here</p>
                                                        <span>or click to browse</span>

                                                        <input type="file" name="document_filepath1[]"
                                                            accept="image/*,application/pdf">
                                                        <div class="file-preview d-none"></div>
                                                        @if ($row && $row->document_filepath1)
                                                            @php
                                                                $filePath = asset(
                                                                    'storage/employee_docs/' . $row->document_filepath1,
                                                                );
                                                                $ext = pathinfo(
                                                                    $row->document_filepath1,
                                                                    PATHINFO_EXTENSION,
                                                                );
                                                            @endphp  

                                                            <div class="file-preview db-preview">
                                                                @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                                                                    <img src="{{ $filePath }}"
                                                                        onclick="window.open('{{ $filePath }}','_blank')">
                                                                @else
                                                                    <div class="pdf-preview"
                                                                        onclick="window.open('{{ $filePath }}','_blank')">
                                                                        <i class="ri-file-pdf-2-line text-danger"></i>
                                                                        <span>{{ basename($row->document_filepath1) }}</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>

                                                    {{-- FILE NAME --}}
                                                    @if ($row && $row->document_filepath1)
                                                        <small class="uploaded-file-name">
                                                            Uploaded: {{ basename($row->document_filepath1) }}
                                                        </small>
                                                    @endif

                                                    {{-- VIEW BUTTON --}}
                                                    <div class="upload-actions">
                                                        @if ($row && $row->document_filepath1)
                                                            <a href="{{ asset('storage/employee_docs/' . $row->document_filepath1) }}"
                                                                target="_blank" class="btn btn-outline-primary btn-sm">
                                                                View
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                    {{-- SAVE / UPDATE ALL --}}
                                    <div class="text-center mt-3">
                                        <button type="button" class="btn btn-primary btn-sm save-single-doc"
                                            data-has-doc="{{ $doc->count() > 0 ? 1 : 0 }}">
                                            {{ $doc->count() > 0 ? 'Update All' : 'Save All' }}
                                        </button>
                                    </div>

                                    {{-- ✅ SALARY SLIPS --}}
                                @elseif ($type === 'salary_slips')
                                    <div class="row g-3">
                                        @for ($i = 1; $i <= 3; $i++)
                                            @php

                                                $row = $doc[$i - 1] ?? null;
                                            @endphp

                                            <div class="col-lg-6 col-md-6">
                                                <div class="doc-card h-100">
                                                    <div class="doc-title">Salary Slip {{ $i }}</div>

                                                    <div class="drop-zone">
                                                        <i class="ri-file-upload-line"></i>
                                                        <p>Drag & drop file here</p>
                                                        <span>or click to browse</span>

                                                        <input type="file" name="document_filepath1[]"
                                                            accept="image/*,application/pdf">
                                                        <div class="file-preview d-none"></div>
                                                        @if ($row && $row->document_filepath1)
                                                            @php
                                                                $filePath = asset(
                                                                    'storage/employee_docs/' . $row->document_filepath1,
                                                                );
                                                                $ext = pathinfo(
                                                                    $row->document_filepath1,
                                                                    PATHINFO_EXTENSION,
                                                                );
                                                            @endphp

                                                            <div class="file-preview db-preview">
                                                                @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                                                                    <img src="{{ $filePath }}"
                                                                        onclick="window.open('{{ $filePath }}','_blank')">
                                                                @else
                                                                    <div class="pdf-preview"
                                                                        onclick="window.open('{{ $filePath }}','_blank')">
                                                                        <i class="ri-file-pdf-2-line text-danger"></i>
                                                                        <span>{{ basename($row->document_filepath1) }}</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>

                                                    {{-- FILE NAME --}}
                                                    @if ($row && $row->document_filepath1)
                                                        <small class="uploaded-file-name">
                                                            Uploaded: {{ basename($row->document_filepath1) }}
                                                        </small>
                                                    @endif

                                                    {{-- VIEW --}}
                                                    <div class="upload-actions">
                                                        @if ($row && $row->document_filepath1)
                                                            <a href="{{ asset('storage/employee_docs/' . $row->document_filepath1) }}"
                                                                target="_blank" class="btn btn-outline-primary btn-sm">
                                                                View
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>

                                    {{-- SAVE / UPDATE ALL --}}
                                    <div class="text-center mt-4">
                                        <button type="button" class="btn btn-primary btn-sm save-single-doc"
                                            data-has-doc="{{ $doc->count() > 0 ? 1 : 0 }}">
                                            {{ $doc->count() > 0 ? 'Update All' : 'Save All' }}
                                        </button>
                                    </div>

                                    {{-- ✅ SINGLE FILE DOCUMENTS --}}
                                @else
                                    <div class="row g-3 ">
                                        <div class="col-lg-6">
                                            <div class="doc-card h-100 ">
                                                <div class="doc-title">{{ $label }}</div>

                                                <div class="drop-zone">
                                                    <i class="ri-upload-cloud-2-line"></i>
                                                    <p>Drag & drop file here or click to upload</p>
                                                    <span>PDF / Image allowed</span>

                                                    <input type="file" name="document_filepath1"
                                                        accept="image/*,application/pdf">
                                                    <div class="file-preview d-none"></div>

                                                    @if ($doc->first()?->document_filepath1)
                                                        @php
                                                            $filePath = asset(
                                                                'storage/employee_docs/' .
                                                                    $doc->first()->document_filepath1,
                                                            );
                                                            $ext = pathinfo(
                                                                $doc->first()->document_filepath1,
                                                                PATHINFO_EXTENSION,
                                                            );
                                                        @endphp

                                                        <div class="file-preview db-preview">
                                                            @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                                                                <img src="{{ $filePath }}"
                                                                    onclick="window.open('{{ $filePath }}','_blank')">
                                                            @else
                                                                <div class="pdf-preview"
                                                                    onclick="window.open('{{ $filePath }}','_blank')">
                                                                    <i class="ri-file-pdf-2-line text-danger"></i>
                                                                    <span>{{ basename($doc->first()->document_filepath1) }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="upload-actions">
                                                    @if ($doc->first()?->document_filepath1)
                                                        <a href="{{ asset('storage/employee_docs/' . $doc->first()->document_filepath1) }}"
                                                            target="_blank" class="btn btn-outline-primary btn-sm">View</a>
                                                    @endif

                                                    <button type="button" class="btn btn-primary btn-sm save-single-doc"
                                                        data-has-doc="{{ $doc->count() > 0 ? 1 : 0 }}">
                                                        {{ $doc->count() > 0 ? 'Update' : 'Save' }}
                                                    </button>
                                                </div>

                                                @if ($doc->first()?->document_filepath1)
                                                    <small class="uploaded-file-name">
                                                        Uploaded: {{ basename($doc->first()->document_filepath1) }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        @if ($type === 'aadhaar')
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="doc-card">
                                                    <div class="doc-title">Aadhaar Card (Back)</div>

                                                    <div class="drop-zone">
                                                        <i class="ri-upload-cloud-2-line"></i>
                                                        <p>Drag & drop back side or click to upload</p>
                                                        <span>PDF / Image allowed</span>

                                                        <input type="file" name="document_filepath2"
                                                            accept="image/*,application/pdf">
                                                        <div class="file-preview d-none"></div>

                                                        @if ($doc->first()?->document_filepath1)
                                                            @php
                                                                $filePath = asset(
                                                                    'storage/employee_docs/' .
                                                                        $doc->first()->document_filepath2,
                                                                );
                                                                $ext = pathinfo(
                                                                    $doc->first()->document_filepath2,
                                                                    PATHINFO_EXTENSION,
                                                                );
                                                            @endphp

                                                            <div class="file-preview db-preview">
                                                                @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                                                                    <img src="{{ $filePath }}"
                                                                        onclick="window.open('{{ $filePath }}','_blank')">
                                                                @else
                                                                    <div class="pdf-preview"
                                                                        onclick="window.open('{{ $filePath }}','_blank')">
                                                                        <i class="ri-file-pdf-2-line text-danger"></i>
                                                                        <span>{{ basename($doc->first()->document_filepath1) }}</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>

                                                    @if ($doc->first()?->document_filepath2)
                                                        <small class="uploaded-file-name">
                                                            Uploaded: {{ basename($doc->first()->document_filepath2) }}
                                                        </small>
                                                    @endif

                                                    <div class="upload-actions">
                                                        @if ($doc->first()?->document_filepath2)
                                                            <a href="{{ asset('storage/employee_docs/' . $doc->first()->document_filepath2) }}"
                                                                target="_blank" class="btn btn-outline-primary btn-sm">
                                                                View
                                                            </a>
                                                        @endif

                                                        <button type="button"
                                                            class="btn btn-primary btn-sm save-single-doc"
                                                            data-has-doc="{{ $doc->count() > 0 ? 1 : 0 }}">
                                                            {{ $doc->count() > 0 ? 'Update' : 'Save' }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                @endif
                            </div>
                            <hr>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.save-single-doc').on('click', function() {
                
                let btn = $(this);
                let section = btn.closest('.single-doc-section');
                let formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('employee_id', '{{ $employee_id }}');

                let fileSelected = false;
                section.find('input[type="file"]').each(function() {
                    if ($(this)[0].files.length > 0) fileSelected = true;
                });

                if (!fileSelected) {
                    alert('Please select at least one file to upload.');
                    return;
                }
                section.find('input, select').each(function() {
                    let name = $(this).attr('name');
                    let files = $(this)[0].files;
                    if (files && files.length > 0) {
                        if (name.endsWith('[]')) {
                            for (let i = 0; i < files.length; i++) formData.append(name, files[i]);
                        } else {
                            formData.append(name, files[0]);
                        }
                    } else {
                        formData.append(name, $(this).val());
                    }
                });
                btn.prop('disabled', true).text('Saving...');
                $.ajax({
                    url: "{{ route('employee.document.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        btn.text('Saved ✅').removeClass('btn-primary').addClass('btn-success');
                        setTimeout(() => {
                            btn.text('Update All').prop('disabled', false).removeClass(
                                'btn-success').addClass('btn-primary');
                            location.reload();
                        }, 200);
                    },
                    error: function() {
                        btn.text('Retry').prop('disabled', false);
                        alert('Something went wrong! Please try again.');
                    }
                });
            });
            $(document).on('change', '.drop-zone input[type="file"]', function() {
                let file = this.files[0];
                if (!file) return;

                let dropZone = $(this).closest('.drop-zone');

                // OLD DB preview hatao
                dropZone.find('.db-preview').remove();

                let previewBox = dropZone.find('.file-preview').first();
                previewBox.removeClass('d-none').html('');

                let fileURL = URL.createObjectURL(file);

                if (file.type.startsWith('image/')) {
                    previewBox.html(`<img src="${fileURL}">`);
                } else {
                    previewBox.html(`
            <div class="pdf-preview">
                <i class="ri-file-pdf-2-line text-danger"></i>
                <span>${file.name}</span>
            </div>
        `);
                }

                previewBox.off('click').on('click', function(e) {
                    e.stopPropagation();
                    window.open(fileURL, '_blank');
                });

                dropZone.addClass('has-preview');
            });

        });
    </script>
    
@endpush
