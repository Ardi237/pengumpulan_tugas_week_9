<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class MaterialController extends Controller
{
    public function index(Course $course): View
    {
        Gate::authorize('view', $course);

        $materials = $course->materials()
            ->with('uploader')
            ->latest()
            ->paginate(10);

        return view('courses.materials.index', compact('course', 'materials'));
    }

    public function create(Course $course): View
    {
        Gate::authorize('create', [Material::class, $course]);

        return view('courses.materials.create', compact('course'));
    }

    public function store(StoreMaterialRequest $request, Course $course): RedirectResponse
    {
        $validated = $request->validated();
        $filePath = $request->file('file')->store("materials/{$course->getKey()}", 'local');

        if (! is_string($filePath) || $filePath === '') {
            throw new RuntimeException('File materi gagal disimpan.');
        }

        try {
            $material = $course->materials()->create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'file_path' => $filePath,
                'uploaded_by' => $request->user()->getKey(),
            ]);
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($filePath);

            throw $exception;
        }

        return redirect()
            ->route('materials.show', $material)
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    public function show(Material $material): View
    {
        Gate::authorize('view', $material);

        $material->load(['course.lecturer', 'uploader']);

        return view('materials.show', compact('material'));
    }

    public function edit(Material $material): View
    {
        Gate::authorize('update', $material);

        $material->load('course');

        return view('materials.edit', compact('material'));
    }

    public function update(UpdateMaterialRequest $request, Material $material): RedirectResponse
    {
        $validated = $request->validated();
        $oldFilePath = $material->file_path;
        $newFilePath = null;

        if ($request->hasFile('file')) {
            $storedPath = $request->file('file')->store("materials/{$material->course_id}", 'local');

            if (! is_string($storedPath) || $storedPath === '') {
                throw new RuntimeException('File materi pengganti gagal disimpan.');
            }

            $newFilePath = $storedPath;
        }

        try {
            $material->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'file_path' => $newFilePath ?? $oldFilePath,
            ]);
        } catch (Throwable $exception) {
            if ($newFilePath !== null) {
                Storage::disk('local')->delete($newFilePath);
            }

            throw $exception;
        }

        if ($newFilePath !== null && filled($oldFilePath) && $oldFilePath !== $newFilePath) {
            Storage::disk('local')->delete($oldFilePath);
        }

        return redirect()
            ->route('materials.show', $material)
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Material $material): RedirectResponse
    {
        Gate::authorize('delete', $material);

        $course = $material->course;
        $filePath = $material->file_path;

        $material->delete();

        if (filled($filePath)) {
            Storage::disk('local')->delete($filePath);
        }

        return redirect()
            ->route('courses.materials.index', $course)
            ->with('success', 'Materi berhasil dihapus.');
    }

    public function download(Material $material): StreamedResponse
    {
        Gate::authorize('view', $material);

        $filePath = $material->file_path;

        abort_if(blank($filePath) || ! Storage::disk('local')->exists($filePath), 404, 'File materi tidak ditemukan.');

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $baseName = Str::slug($material->title) ?: 'materi';
        $downloadName = $extension === '' ? $baseName : "{$baseName}.{$extension}";

        return Storage::disk('local')->download($filePath, $downloadName);
    }
}
