const p = new URLSearchParams(location.hash.replace("#", ""));
if (p.has("i"))
{
	document.getElementById("input").value = p.get("i");
}
document.getElementById("input").addEventListener("input", function (event)
{
	history.replaceState({}, undefined, event.target.value ? `#i=${encodeURIComponent(event.target.value)}` : location.pathname);
});

function get_input()
{
	pluto_give_file("input", document.getElementById("input").value)
}
