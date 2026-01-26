<h1>Edit Dokumen</h1>

<form action="{{ route('documents.update', $document->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Judul</label><br>
    <input type="text" name="title" value="{{ $document->title }}"><br><br>

    <label>Unit</label><br>
    <input type="text" name="unit" value="{{ $document->unit }}"><br><br>

    <label>Status</label><br>
    <select name="status">
        <option value="AKTIF" @selected($document->status=='AKTIF')>AKTIF</option>
        <option value="NONAKTIF" @selected($document->status=='NONAKTIF')>NONAKTIF</option>
    </select><br><br>

    <button type="submit">Update</button>
</form>
